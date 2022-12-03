<?php

namespace Acelle\Http\Controllers;

use Illuminate\Http\Request;
use Acelle\Model\Template;
use Acelle\Model\Setting;
use App;
use File;
use Acelle\Library\Tool;
use function Acelle\Helpers\xml_to_array;

class TemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('templates.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listing(Request $request)
    {
        if ($request->from == 'mine') {
            $templates = $request->user()->customer->templates()->email();
        } elseif ($request->from == 'gallery') {
            $templates = Template::shared()->email();
        }

        // view
        $view = $request->view ? $request->view : 'list';

        // sort, pagination
        $templates = $templates->search($request->keyword)
            ->email()
            ->categoryUid($request->category_uid)
            ->notPreserved()
            ->orderBy($request->sort_order, $request->sort_direction)
            ->paginate($request->per_page ? $request->per_page : ($view == 'grid' ? 8 : 15));


        return view('templates._list_' . $view, [
            'templates' => $templates,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // Generate info
        $user = $request->user();
        $template = new Template();

        // authorize
        if (!$request->user()->customer->can('create', Template::class)) {
            return $this->notAuthorized();
        }

        // Get old post values
        if (null !== $request->old()) {
            $template->fill($request->old());
        }

        return view('templates.create', [
            'template' => $template,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $uid)
    {
        // Generate info
        $user = $request->user();
        $template = Template::findByUid($uid);

        // authorize
        if (!$request->user()->customer->can('update', $template)) {
            return $this->notAuthorized();
        }

        // Get old post values
        if (null !== $request->old()) {
            $template->fill($request->old());
        }

        return view('templates.edit', [
            'template' => $template,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Generate info
        $user = $request->user();
        $template = Template::findByUid($request->uid);

        // authorize
        if (!$request->user()->customer->can('update', $template)) {
            return $this->notAuthorized();
        }

        // Save template
        $template->fill($request->all());

        $rules = array(
            'name' => 'required',
            'content' => 'required',
        );

        // make validator
        $validator = \Validator::make($request->all(), $rules);

        // redirect if fails
        if ($validator->fails()) {
            // faled
            return response()->json($validator->errors(), 400);
        }

        $template->updateContent($request->content);

        // success
        return response()->json([
            'status' => 'success',
            'message' => trans('messages.template.updated'),
        ], 201);
    }

    /**
     * Upload template.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function uploadTemplate(Request $request)
    {
        // authorize
        if (!$request->user()->customer->can('create', Template::class)) {
            return $this->notAuthorized();
        }

        // validate and save posted data
        if ($request->isMethod('post')) {
            $template = Template::uploadTemplate($request);

            $request->session()->flash('alert-success', trans('messages.template.uploaded'));
            return redirect()->action('TemplateController@index');
        } else {
            return view('templates.upload');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        if (isSiteDemo()) {
            return response()->json([
                'status' => 'notice',
                'message' => trans('messages.operation_not_allowed_in_demo'),
            ], 403);
        }

        $templates = Template::whereIn(
            'uid',
            is_array($request->uids) ? $request->uids : explode(',', $request->uids)
        );
        $total = $templates->count();
        $deleted = 0;
        foreach ($templates->get() as $template) {
            // authorize
            if ($request->user()->customer->can('delete', $template)) {
                $template->deleteAndCleanup();
                $deleted += 1;
            }
        }

        echo trans('messages.templates.deleted', [ 'deleted' => $deleted, 'total' => $total]);
    }

    /**
     * Preview template.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function preview(Request $request, $id)
    {
        $template = Template::findByUid($id);

        // authorize
        if (!$request->user()->customer->can('preview', $template)) {
            return $this->notAuthorized();
        }

        return view('templates.preview', [
            'template' => $template,
        ]);
    }

    /**
     * Copy template.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function copy(Request $request)
    {
        $template = Template::findByUid($request->uid);

        if ($request->isMethod('post')) {
            // authorize
            if (!$request->user()->customer->can('copy', $template)) {
                return $this->notAuthorized();
            }

            $template->copy([
                'name' => $request->name,
                'customer_id' => $request->user()->customer->id,
            ]);

            echo trans('messages.template.copied');
            return;
        }

        return view('templates.copy', [
            'template' => $template,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function builderEdit(Request $request, $uid)
    {
        // Generate info
        $user = $request->user();
        $template = Template::findByUid($uid);

        // authorize
        if (!$request->user()->customer->can('update', $template)) {
            return $this->notAuthorized();
        }

        // validate and save posted data
        if ($request->isMethod('post')) {
            $rules = array(
                'content' => 'required',
            );

            $this->validate($request, $rules);

            $template->updateContent($request->content);

            return response()->json([
                'status' => 'success',
            ]);
        }

        return view('templates.builder.edit', [
            'template' => $template,
            'templates' => $request->user()->customer->getBuilderTemplates(),
        ]);
    }

    /**
     * Change template from exist template.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function builderChangeTemplate(Request $request, $uid, $change_uid)
    {
        // Generate info
        $user = $request->user();
        $template = Template::findByUid($uid);
        $changeTemplate = Template::findByUid($change_uid);

        // authorize
        if (!$request->user()->customer->can('update', $template)) {
            return $this->notAuthorized();
        }

        $template->changeTemplate($changeTemplate);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function builderEditContent(Request $request, $uid)
    {
        // Generate info
        $user = $request->user();
        $template = Template::findByUid($uid);

        // authorize
        if (!$request->user()->customer->can('update', $template)) {
            return $this->notAuthorized();
        }

        return view('templates.builder.content', [
            'content' => $template->content,
        ]);
    }

    /**
     * Upload asset to builder.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function uploadTemplateAssets(Request $request, $uid)
    {
        $template = Template::findByUid($uid);

        // authorize
        if (!$request->user()->customer->can('update', $template)) {
            return $this->notAuthorized();
        }

        if ($request->assetType == 'upload') {
            $assetUrl = $template->uploadAsset($request->file('file'));
        } elseif ($request->assetType == 'url') {
            $assetUrl = $template->uploadAssetFromUrl($request->url);
        } elseif ($request->assetType == 'base64') {
            $assetUrl = $template->uploadAssetFromBase64($request->url_base64);
        }

        return response()->json([
            'url' => $assetUrl
        ]);
    }

    /**
     * Create template / temlate selection.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function builderCreate(Request $request)
    {
        // authorize
        if (!$request->user()->customer->can('create', Template::class)) {
            return $this->notAuthorized();
        }

        // validate and save posted data
        if ($request->isMethod('post')) {
            // Get selected template
            $selectedTemplate = Template::findByUid($request->template);

            // Copy
            $template = $request->user()->customer->copyTemplateAs($selectedTemplate, $request->name);

            return redirect()->action('TemplateController@builderEdit', $template->uid);
        } else {
            $template = new Template();
            $template->name = trans('messages.untitled_template');

            return view('templates.builder.create', [
                'template' => $template,
            ]);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function builderTemplates(Request $request)
    {
        // category
        $category = \Acelle\Model\TemplateCategory::findByUid($request->category_uid);

        // sort, pagination
        $templates = $category->templates()->shared()->email()->search($request->keyword)
            ->orderBy($request->sort_order, $request->sort_direction)
            ->paginate($request->per_page);

        // authorize
        if (!$request->user()->customer->can('create', Template::class)) {
            return $this->notAuthorized();
        }

        return view('templates.builder.templates', [
            'templates' => $templates,
        ]);
    }

    /**
     * Update template thumb.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateThumb(Request $request, $uid)
    {
        $template = Template::findByUid($uid);

        // authorize
        if (!$request->user()->customer->can('update', $template)) {
            return $this->notAuthorized();
        }

        if ($request->isMethod('post')) {
            // make validator
            $validator = \Validator::make($request->all(), [
                'file' => 'required',
            ]);

            // redirect if fails
            if ($validator->fails()) {
                return response()->view('templates.updateThumb', [
                    'template' => $template,
                    'errors' => $validator->errors(),
                ], 400);
            }

            // update thumb
            $template->uploadThumbnail($request->file);

            return response()->json([
                'status' => 'success',
                'message' => trans('messages.template.thumb.uploaded'),
            ], 201);
        }

        return view('templates.updateThumb', [
            'template' => $template,
        ]);
    }

    /**
     * Update template thumb url.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateThumbUrl(Request $request, $uid)
    {
        $template = Template::findByUid($uid);

        // authorize
        if (!$request->user()->customer->can('update', $template)) {
            return $this->notAuthorized();
        }

        if ($request->isMethod('post')) {
            // make validator
            $validator = \Validator::make($request->all(), [
                'url' => 'required|url',
            ]);

            // redirect if fails
            if ($validator->fails()) {
                return response()->view('templates.updateThumbUrl', [
                    'template' => $template,
                    'errors' => $validator->errors(),
                ], 400);
            }

            // update thumb
            $template->uploadThumbnailUrl($request->url);

            return response()->json([
                'status' => 'success',
                'message' => trans('messages.template.thumb.uploaded'),
            ], 201);
        }

        return view('templates.updateThumbUrl', [
            'template' => $template,
        ]);
    }

    /**
     * Template categories.
     *
     * @return \Illuminate\Http\Response
     */
    public function categories(Request $request, $uid)
    {
        $template = Template::findByUid($uid);

        // authorize
        if (!$request->user()->customer->can('update', $template)) {
            return $this->notAuthorized();
        }

        if ($request->isMethod('post')) {
            foreach ($request->categories as $key => $value) {
                $category = \Acelle\Model\TemplateCategory::findByUid($key);
                if ($value == 'true') {
                    $template->addCategory($category);
                } else {
                    $template->removeCategory($category);
                }
            }
        }

        return view('templates.categories', [
            'template' => $template,
        ]);
    }

    public function export(Request $request)
    {
        $template = Template::findByUid($request->uid);

        $zipPath = $template->createTmpZip();

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    public function changeName(Request $request)
    {
        $template = Template::findByUid($request->uid);

        // authorize
        if (!$request->user()->customer->can('update', $template)) {
            return $this->notAuthorized();
        }

        if ($request->isMethod('post')) {
            // change name
            $validator = $template->changeName($request->name);

            if ($validator->fails()) {
                return response()->view('templates.changeName', [
                    'template' => $template,
                    'errors' => $validator->errors(),
                ], 400);
            }

            return response()->json([
                'status' => 'success',
                'message' => trans('messages.template.name.changed'),
            ], 201);
        }

        return view('templates.changeName', [
            'template' => $template,
        ]);
    }

    public function parseRss(Request $request)
    {
        return parseRss($request->config);
    }
}
