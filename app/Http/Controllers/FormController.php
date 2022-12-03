<?php

namespace Acelle\Http\Controllers;

use Illuminate\Http\Request;
use Acelle\Model\Form;
use Acelle\Model\Template;
use Acelle\Model\MailList;

class FormController extends Controller
{
    public function index(Request $request)
    {
        return view('forms.index');
    }

    public function create(Request $request)
    {
        $form = Form::newDefault($request->user()->customer);

        if ($request->isMethod('post')) {
            $validator = $form->createFromArray($request->all());

            // redirect if fails
            if ($validator->fails()) {
                return response()->view('forms.create', [
                    'form' => $form,
                    'errors' => $validator->errors(),
                ], 400);
            }


            return redirect()->action('FormController@build', [
                'uid' => $form->uid,
            ])->with('alert-success', trans('messages.form.created', [
                'name' => $form->name,
            ]));
        }

        return view('forms.create', [
            'form' => $form,
        ]);
    }

    public function templates(Request $request)
    {
        $templates = Template::popup()->shared()
            ->search($request->keyword)
            ->orderBy($request->sort_order, $request->sort_direction)
            ->paginate(8);

        return view('forms.templates', [
            'templates' => $templates,
        ]);
    }

    public function list(Request $request)
    {
        // sort, pagination
        $forms = $request->user()->customer->forms()->search($request->keyword)
            ->filter($request->all())
            ->orderBy($request->sort_order, $request->sort_direction)
            ->paginate($request->per_page);

        return view('forms.list', [
            'forms' => $forms,
        ]);
    }

    public function delete(Request $request)
    {
        if (isSiteDemo()) {
            return response()->json([
                'status' => 'notice',
                'message' => trans('messages.operation_not_allowed_in_demo'),
            ], 403);
        }

        $forms = Form::whereIn(
            'uid',
            is_array($request->uids) ? $request->uids : explode(',', $request->uids)
        );

        $total = $forms->count();
        $deleted = 0;
        foreach ($forms->get() as $form) {
            // authorize
            if ($request->user()->customer->can('delete', $form)) {
                $form->delete();
                $deleted += 1;
            }
        }

        return response()->json([
            'message' => trans('messages.forms.deleted', [ 'deleted' => $deleted, 'total' => $total]),
        ]);
    }

    public function build(Request $request)
    {
        $form = Form::findByUid($request->uid);

        // authorize
        if (\Gate::denies('update', $form)) {
            return $this->notAuthorized();
        }

        $templates = Template::popup()->shared()->get();

        return view('forms.build', [
            'form' => $form,
            'templates' => $templates,
        ]);
    }

    public function builderContent(Request $request)
    {
        // Generate info
        $form = Form::findByUid($request->uid);

        // authorize
        if (\Gate::denies('update', $form)) {
            return $this->notAuthorized();
        }

        $content = view('forms.content', [
            'template' => $form->template,
            'content' => $form->template->content,
        ]);

        return response($content)->header('Access-Control-Allow-Origin', '*');
    }

    public function builder(Request $request)
    {
        $form = Form::findByUid($request->uid);

        // authorize
        if (\Gate::denies('update', $form)) {
            return $this->notAuthorized();
        }

        // form fields for Editor
        $formFields = $form->mailList->getFields->map(function ($field) {
            return [
                'type' => $field->tag == 'EMAIL' ? 'email' : $field->type,
                'label' => $field->label,
                'name' => $field->tag,
                'visible' => $field->visible,
                'required' => $field->required,
                'default' => $field->default_value,
                'options' => $field->fieldOptions->map(function ($option) {
                    return ['value' => $option->value, 'text' => $option->label];
                })->toArray(),
            ];
        })->toArray();

        return view('forms.builder', [
            'form' => $form,
            'formFields' => $formFields,
        ]);
    }

    public function frontendContent(Request $request)
    {
        // Generate info
        $form = Form::findByUid($request->uid);

        // Language for frontend
        $this->frontendLanguage($form);

        if ($request->preview) {
            $html = $request->session()->get('form-preview-content-' . $form->uid);
            $content = $form->renderedContent($html);
        } else {
            $content = $form->renderedContent();
        }

        $content = view('forms.frontend.content', [
            'form' => $form,
            'content' => $content,
        ]);

        return response($content)->header('Access-Control-Allow-Origin', '*');
    }

    public function preview(Request $request)
    {
        $form = Form::findByUid($request->uid);

        // authorize
        if (\Gate::denies('read', $form)) {
            return $this->notAuthorized();
        }

        $request->session()->put('form-preview-content-' . $form->uid, $request->content);
    }

    public function frontendPopup(Request $request)
    {
        $form = Form::findByUid($request->uid);

        // Language for frontend
        $this->frontendLanguage($form);

        $content = view('forms.frontend.popup', [
            'form' => $form,
        ]);

        return response($content)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Content-Type', 'application/javascript');
    }

    public function settings(Request $request)
    {
        $form = Form::findByUid($request->uid);

        try {
            $form->saveSettingsFromArray($request->all());

            return response()->json([
                'message' => trans('messages.form.settins_saved'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    public function connect(Request $request)
    {
        $form = Form::findByUid($request->uid);

        if ($request->isMethod('post')) {
            // make validator
            $validator = \Validator::make($request->all(), [
                'website_uid' => 'required',
            ]);

            // redirect if fails
            if ($validator->fails()) {
                return response()->view('forms.connect', [
                    'form' => $form,
                    'errors' => $validator->errors(),
                ], 400);
            }

            $site = \Acelle\Model\Website::findByUid($request->website_uid);
            $form->connect($site);

            return response()->json([
                'message' => trans('messages.form.connected', [
                    'site' => $site->title,
                ]),
            ]);
        }

        return view('forms.connect', [
            'form' => $form,
        ]);
    }

    public function disconnect(Request $request)
    {
        $form = Form::findByUid($request->uid);

        // authorize
        if (\Gate::denies('update', $form)) {
            return $this->notAuthorized();
        }

        if ($request->isMethod('post')) {
            $form->disconnect();

            return response()->json([
                'message' => trans('messages.form.disconnected'),
            ]);
        }

        return view('forms.connect', [
            'form' => $form,
        ]);
    }

    public function publish(Request $request)
    {
        $forms = Form::whereIn(
            'uid',
            is_array($request->uids) ? $request->uids : explode(',', $request->uids)
        );

        $total = $forms->count();
        $done = 0;
        foreach ($forms->get() as $form) {
            // authorize
            if ($request->user()->customer->can('publish', $form)) {
                $form->publish();
                $done += 1;
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => trans('messages.forms.published', [ 'done' => $done, 'total' => $total]),
        ]);
    }

    public function unpublish(Request $request)
    {
        $forms = Form::whereIn(
            'uid',
            is_array($request->uids) ? $request->uids : explode(',', $request->uids)
        );

        $total = $forms->count();
        $done = 0;
        foreach ($forms->get() as $form) {
            // authorize
            if ($request->user()->customer->can('unpublish', $form)) {
                $form->unpublish();
                $done += 1;
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => trans('messages.forms.unpublished', [ 'done' => $done, 'total' => $total]),
        ]);
    }

    public function frontendLanguage($form)
    {
        // Language
        if (is_object($form->customer) && is_object($form->customer->language)) {
            \App::setLocale($form->customer->language->code);
            \Carbon\Carbon::setLocale($form->customer->language->code);
        }
    }

    public function frontendSave(Request $request)
    {
        $form = Form::findByUid($request->uid);
        $list = $form->mailList;

        // Language for frontend
        $this->frontendLanguage($form);

        try {
            // Create subscriber
            list($validator, $subscriber) = $list->subscribe($request, MailList::SOURCE_EMBEDDED_FORM);
        } catch (\Exception $ex) {
            return response()->json([
                'message' => $ex->getMessage()
            ], 400);
        }

        // if fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        return response()->json([
            'message' => 'OK',
        ]);
    }

    public function changeTemplate(Request $request)
    {
        $form = Form::findByUid($request->uid);
        $template = Template::findByUid($request->template_uid);

        $form->changeTemplate($template);

        return response()->json([
            'url' => action('FormController@builderContent', [
                'uid' => $form->uid,
            ]),
            'saveUrl' => action('TemplateController@builderEdit', [
                'uid' => $form->template->uid,
            ]),
            'uploadAssetUrl' => action('TemplateController@uploadTemplateAssets', [
                'uid' => $form->template->uid,
            ])

        ]);
    }
}
