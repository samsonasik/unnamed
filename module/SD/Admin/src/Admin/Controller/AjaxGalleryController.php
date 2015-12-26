<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.25
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace SD\Admin\Controller;

use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Zend\File\Transfer\Adapter\Http;
use Zend\Validator\File\Extension;
use Zend\Validator\File\IsImage;
use Zend\Validator\File\Size;
use Zend\View\Model\JsonModel;

final class AjaxGalleryController extends BaseController
{
    /**
     * Deleted image with from a given src.
     *
     * @return bool
     */
    public function deleteImageAction()
    {
        /** @var Request $request */
        $request = $this->getRequest();
        $status = false;

        if ($request->isPost()) {
            $data = $request->getPost()->toArray();

            if ($request->isXmlHttpRequest()) {
                if (is_file('public'.$data['img'])) {
                    unlink('public'.$data['img']);
                    $status = true;
                }
            }
        }

        return $status;
    }

    /**
     * Get all files from all folders and list them in the gallery
     * getcwd() is there to make the work with images path easier.
     *
     * @return JsonModel
     */
    public function filesAction()
    {
        chdir(getcwd().'/public/');
        $this->makeDir();
        $this->getView()->setTerminal(true);
        $dir = new RecursiveDirectoryIterator('userfiles/', FilesystemIterator::SKIP_DOTS);
        $iterator = new RecursiveIteratorIterator($dir, RecursiveIteratorIterator::SELF_FIRST);
        $iterator->setMaxDepth(50);
        $files = $this->extractImages($iterator);

        chdir(dirname(getcwd()));
        $model = new JsonModel();
        $model->setVariables(['files' => $files]);

        return $model;
    }

    /**
     * @param RecursiveIteratorIterator $iterator
     *
     * @return array
     */
    private function extractImages($iterator)
    {
        $files = [];
        $index = 0;

        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $files[$index]['filelink'] = DIRECTORY_SEPARATOR.$file->getPath().DIRECTORY_SEPARATOR.$file->getFilename();
                $files[$index]['filename'] = $file->getFilename();
                $index = $index + 1;
            }
        }

        return $files;
    }

    /**
     * @param string $publicFolder
     *
     * @return void
     */
    private function makeDir($publicFolder = 'public/')
    {
        if (!is_dir($publicFolder.'userfiles/'.date('Y_M').'/images/')) {
            mkdir($publicFolder.'userfiles/'.date('Y_M').'/images/', 0750, true);
        }
    }

    /**
     * Upload all images async.
     *
     * @return array
     */
    public function prepareImages()
    {
        $adapter = new Http();
        $size = new Size(['min' => '10kB', 'max' => '5MB', 'useByteString' => true]);
        $extension = new Extension(['jpg', 'gif', 'png', 'jpeg', 'bmp', 'webp', 'svg'], true);

        $adapter->setValidators([$size, new IsImage(), $extension]);

        $this->makeDir('public/');

        $adapter->setDestination('public/userfiles/'.date('Y_M').'/images/');

        $data = $this->uploadFiles($adapter);

        return new JsonModel($data);
    }

    /**
     * @param Http $adapter
     *
     * @return array
     */
    private function uploadFiles(Http $adapter)
    {
        $uploadStatus = [];

        foreach ($adapter->getFileInfo() as $key => $file) {
            if ($key !== 'preview') {
                // @codeCoverageIgnoreStart
                $arr1 = $this->validateUploadedFileName($adapter, $file['name']);
                $arr2 = $this->validateUploadedFile($adapter, $file['name']);
                $uploadStatus = array_merge_recursive($arr1, $arr2);
                // @codeCoverageIgnoreEnd
            }
        }

        return $uploadStatus;
    }

    /**
     * See if file has been received and uploaded.
     *
     * @param Http   $adapter
     * @param string $fileName
     *
     * @return array
     */
    private function validateUploadedFile(Http $adapter, $fileName)
    {
        $uploadStatus = [];
        $adapter->receive($fileName);

        if (!$adapter->isReceived($fileName) && $adapter->isUploaded($fileName)) {
            $uploadStatus['errorFiles'][] = $fileName.' was not uploaded';
        } else {
            $uploadStatus['successFiles'][] = $fileName.' was successfully uploaded';
        }

        return $uploadStatus;
    }

    /**
     * See if file name is valid and it not return alll messages.
     *
     * @param Http   $adapter
     * @param string $fileName
     *
     * @return array
     */
    private function validateUploadedFileName(Http $adapter, $fileName)
    {
        $uploadStatus = [];

        if (!$adapter->isValid($fileName)) {
            foreach ($adapter->getMessages() as $msg) {
                $uploadStatus['errorFiles'][] = $fileName.' '.strtolower($msg);
            }
        }

        return $uploadStatus;
    }
}
