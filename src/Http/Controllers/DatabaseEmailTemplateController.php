<?php

namespace HXM\LaravelDatabaseEmailTemplate\Http\Controllers;

use \Exception;
use HXM\LaravelDatabaseEmailTemplate\Contracts\DatabaseEmailTempalteInterface;
use HXM\LaravelDatabaseEmailTemplate\Facades\DatabaseEmailTemplate;
use HXM\LaravelDatabaseEmailTemplate\Http\Requests\EmailTemplateRequest;
use HXM\LaravelDatabaseEmailTemplate\Models\EmailTemplate;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\ValidationException;

class DatabaseEmailTemplateController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $templates = EmailTemplate::all();

        return DatabaseEmailTemplate::getViewByName("index", compact('templates'));
    }

    function create(EmailTemplate $template)
    {
        $emailsList = EmailTemplate::all();

        return DatabaseEmailTemplate::getViewByName("create", compact('template', 'emailsList'));
    }

    public function store(EmailTemplateRequest $request)
    {
        EmailTemplate::create($request->validated());
        return redirect()->to(DatabaseEmailTemplate::getRouteByName('index'))->with('success', 'Created');
    }

    public function show(Request $request, EmailTemplate $emailTemplate)
    {
        try {

            if (!$emailTemplate->id && $id = $request->input('id')) {
                $emailTemplate = EmailTemplate::findOrFail($id);
            }
            $request->input('mailable') && $emailTemplate->mailable = $request->input('mailable');
            $request->input('content') && $emailTemplate->content = $request->input('content');
            $request->input('type') && $emailTemplate->type = $request->input('type');

            if (empty($emailTemplate->mailable)) {
                throw new Exception('Mailable cannot be empty');
            }
            if (!class_exists($emailTemplate->mailable)) {
                throw new Exception('Class: ' . $emailTemplate->mailable . ' is not exists');
            }

            $mailable = $emailTemplate->mailable::makePreview();

            $mailable->setEmailTemplate($emailTemplate);

            return $mailable->render();
        } catch (Exception $e) {

            echo "<pre>";
            return $e;
        }
    }
    public function edit(EmailTemplate $emailTemplate)
    {
        return DatabaseEmailTemplate::getViewByName("edit", ['template' => $emailTemplate]);
    }

    public function update(EmailTemplateRequest $request, EmailTemplate $emailTemplate)
    {
        $emailTemplate->update($request->validated());
        return redirect()->to(DatabaseEmailTemplate::getRouteByName('index'))->with('success', 'Updated');;
    }

    public function destroy(EmailTemplate $emailTemplate)
    {

        $emailTemplate->delete();
        return redirect()->to(DatabaseEmailTemplate::getRouteByName('index'))->with('success', 'Deleted');;
    }

    public function loadDefaultData()
    {
        $mailable = request()->get('mailable');
        try {

            if (!$mailable || !class_exists($mailable)) {
                throw new Exception('Class: ' . $mailable . ' is not exists');
            }
            /** @var DatabaseEmailTempalteInterface&Mailable $mailable*/
            $mailable = $mailable::makePreview();
            $path = $mailable->getDefaultTemplatePath();
            /** @var \Illuminate\View\View $view */
            $view = view($path);

            ob_start();
            print_r(collect($mailable->buildViewData())->toArray());
            $vars = ob_get_clean();

            return response()->json([
                'vars' => $vars,
                'viewData' => $mailable->viewData,
                'path' => $path,
                'content' => File::get($view->getPath())
            ]);
        } catch (Exception $e) {

            return response()->json(['message' => $e->getMessage(), 'error' => $e], 400);
        }
    }
}
