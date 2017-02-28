<?php
namespace DejwCake\Media\Controller\Media;

use DejwCake\Media\Helpers\UploadedFile;
use DejwCake\Media\Controller\AppController;
use Cake\Event\Event;
use Cake\Log\Log;
use DejwCake\Media\Service\UploadService;

/**
 * Pages Controller
 *
 * @property \DejwCake\StandardCMS\Model\Table\PagesTable $Pages
 */
class UploaderController extends AppController
{
    protected $wysiwygUploadPath;

    /**
     * Before filter callback.
     *
     * @param \Cake\Event\Event $event The beforeRender event.
     * @return \Cake\Network\Response|null|void
     */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        if($this->request->action == 'ckeditorUpload') {
            $this->eventManager()->off($this->Csrf);
        }

    }

    /**
     * Check if the provided user is authorized for the request.
     *
     * @param array|\ArrayAccess|null $user The user to check the authorization of.
     *   If empty the user fetched from storage will be used.
     * @param \Cake\Network\Request|null $request The request to authenticate for.
     *   If empty, the current request will be used.
     * @return bool True if $user is authorized, otherwise false
     */
    public function isAuthorized($user = null) {
        return parent::isAuthorized($user);
    }

    public function upload() {
        $this->RequestHandler->renderAs($this, 'json');
        if ($this->request->data('fileinput')) {
            $files = $this->request->data('fileinput');
//            debug($files);
            foreach ($files as $key => $file) {
                $fileInfo = new UploadedFile($file['tmp_name'], $file['name'], $file['type'], $file['size'], $file['error']);
                $uploaderService = new UploadService();
                $response[] = $uploaderService->upload($fileInfo);
            }
        } else {
            $response = '';
        }
        $this->set(compact('response'));
        $this->set('_serialize', ['response']);
    }

    public function ckeditorUpload()
    {
        $funcNum = $this->request->query["CKEditorFuncNum"];
        $message = '';

        if (!$this->request->data('upload')) {
            $message = __d('media', 'Failed to uplad - file not sent');
        }

        $file = $this->request->data('upload');
        $fileInfo = new UploadedFile($file['tmp_name'], $file['name'], $file['type'], $file['size'], $file['error']);
        $uploaderService = new UploadService();
        $uploaderService->setCkeditor(true);
        $response = $uploaderService->upload($fileInfo);
        $url = DS . $response['original_filedir'];

        echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '$url', '$message');</script>";
    }
}
