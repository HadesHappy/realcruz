<?php

namespace Acelle\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Acelle\Http\Controllers\Controller;
use Acelle\Model\Template;
use Acelle\Model\Setting;
use App;

class FormTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->user()->admin->can('read', new \Acelle\Model\Template())) {
            return $this->notAuthorized();
        }

        return view('admin.form_templates.index', [
            'type' => Template::TYPE_POPUP,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listing(Request $request)
    {
        if (!$request->user()->admin->can('read', new \Acelle\Model\Template())) {
            return $this->notAuthorized();
        }

        // view
        $view = $request->view ? $request->view : 'grid';

        $templates = Template::shared()
            ->popup()
            ->categoryUid($request->category_uid)
            ->search($request->keyword)
            ->orderBy($request->sort_order, $request->sort_direction)
            ->paginate($request->per_page ? $request->per_page : ($view == 'grid' ? 8 : 15));

        return view('admin.form_templates._list_' . $view, [
            'templates' => $templates,
        ]);
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
        if (!$request->user()->admin->can('preview', $template)) {
            return $this->notAuthorized();
        }

        return view('admin.form_templates.preview', [
            'template' => $template,
        ]);
    }
}
