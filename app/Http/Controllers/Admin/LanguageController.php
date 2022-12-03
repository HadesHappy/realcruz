<?php

namespace Acelle\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Acelle\Http\Controllers\Controller;
use Acelle\Library\Tool;
use Acelle\Model\Language;

class LanguageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (\Gate::denies('list', Language::class)) {
            return $this->notAuthorized();
        }

        // @todo: workaround only
        // Language::dump();

        return view('admin.languages.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listing(Request $request)
    {
        if (\Gate::denies('list', Language::class)) {
            return $this->notAuthorized();
        }

        $languages = Language::search($request->keyword)
            ->orderBy($request->sort_order, $request->sort_direction)
            ->paginate($request->per_page);

        return view('admin.languages._list', [
            'languages' => $languages,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $language = Language::newDefaultLanguage();

        // authorize
        if (\Gate::denies('create', $language)) {
            return $this->notAuthorized();
        }

        return view('admin.languages.create', [
            'language' => $language,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // authorize
        if (\Gate::denies('create', Language::class)) {
            return $this->notAuthorized();
        }

        list($language, $validator) = Language::createFromArray($request->all());

        if ($validator !== true) {
            return response()->view('admin.languages.create', [
                'language' => $language,
                'errors' => $validator->errors(),
            ], 400);
        }

        $request->session()->flash('alert-success', trans('messages.language.created'));
        return redirect()->action('Admin\LanguageController@index');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $language = Language::findByUid($id);

        // authorize
        if (\Gate::denies('update', $language)) {
            return $this->notAuthorized();
        }

        return view('admin.languages.edit', [
            'language' => $language,
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
        $language = Language::findByUid($id);

        // authorize
        if (\Gate::denies('update', $language)) {
            return $this->notAuthorized();
        }

        $validator = $language->updateFromRequest($request);

        if ($validator !== true) {
            return response()->view('admin.languages.edit', [
                'language' => $language,
                'errors' => $validator->errors(),
            ], 400);
        }

        $request->session()->flash('alert-success', trans('messages.language.created'));
        return redirect()->action('Admin\LanguageController@index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        if (isSiteDemo()) {
            return response()->json(["message" => trans('messages.operation_not_allowed_in_demo')], 404);
        }

        $languages = Language::whereIn(
            'uid',
            is_array($request->uids) ? $request->uids : explode(',', $request->uids)
        );

        foreach ($languages->get() as $language) {
            // authorize
            if (\Gate::allows('delete', $language)) {
                $language->deleteAndCleanup();
            }
        }

        echo trans('messages.languages.deleted');
    }

    public function translateIntro(Request $request)
    {
        $language = Language::findByUid($request->id);

        return view('admin.languages.translateIntro', [
            'language' => $language,
        ]);
    }

    /**
     * Translate.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function translate(Request $request, $id)
    {
        $language = Language::findByUid($id);
        $currentFile = $language->findFileById($request->file_id);

        // @todo: does it make sense to do this here? just a workaround
        // $language->createOrUpdateTranslationFiles();

        // Prenvent save from demo mod
        if ($this->isDemoMode()) {
            return view('somethingWentWrong', ['message' => trans('messages.operation_not_allowed_in_demo')]);
        }

        // authorize
        if (\Gate::denies('translate', $language)) {
            return $this->notAuthorized();
        }

        // save posted data
        if ($request->isMethod('post')) {
            list($currentFile, $validator) = $language->translateFile($request->file_id, $request->content);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->first(),
                ], 400);
            }

            return response()->json([
                'status' => 'success',
                'message' => trans('messages.language.updated'),
            ]);
        }

        return view('admin.languages.translate', [
            'language' => $language,
            'currentFile' => $currentFile,
            'content' => Language::fileToYaml($currentFile['path']),
        ]);
    }

    /**
     * Disable language.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function disable(Request $request)
    {
        $items = Language::whereIn(
            'uid',
            is_array($request->uids) ? $request->uids : explode(',', $request->uids)
        );

        foreach ($items->get() as $item) {
            // authorize
            if (\Gate::allows('disable', $item)) {
                $item->disable();
            }
        }

        // Redirect to my lists page
        echo trans('messages.languages.disabled');
    }

    /**
     * Disable language.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function enable(Request $request)
    {
        $items = Language::whereIn(
            'uid',
            is_array($request->uids) ? $request->uids : explode(',', $request->uids)
        );

        foreach ($items->get() as $item) {
            // authorize
            if (\Gate::allows('enable', $item)) {
                $item->enable();
            }
        }

        // Redirect to my lists page
        echo trans('messages.languages.enabled');
    }

    /**
     * Download language package.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function download(Request $request, $id)
    {
        $language = Language::findByUid($id);
        $tmpzip = storage_path("tmp/language-" . $language->code . ".zip");

        Tool::zip($language->languageDir(), $tmpzip);

        return response()->download($tmpzip)->deleteFileAfterSend(true);
    }

    /**
     * Upload language package.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function upload(Request $request, $id)
    {
        $language = Language::findByUid($id);

        // validate and save posted data
        if ($request->isMethod('post')) {
            $validator = $language->upload($request);

            if ($validator->fails()) {
                return response()->view('admin.languages.upload', [
                    'language' => $language,
                    'errors' => $validator->errors(),
                ], 400);
            }

            $request->session()->flash('alert-success', trans('messages.language.uploaded'));
            return redirect()->action('Admin\LanguageController@index');
        }

        return view('admin.languages.upload', [
            'language' => $language,
        ]);
    }

    /**
     * Delete confirm message.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteConfirm(Request $request)
    {
        $languages = Language::whereIn(
            'uid',
            is_array($request->uids) ? $request->uids : explode(',', $request->uids)
        );

        return view('admin.languages.delete_confirm', [
            'languages' => $languages,
        ]);
    }
}
