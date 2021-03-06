<?php
/**
 * @category   Chirag
 * @package    Chirag_Banner
 * @author     chirag@czargroup.net
 * @copyright  This file was generated by using Module Creator provided by <developer@czargroup.net>
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Chirag\Banner\Controller\Adminhtml\Post;

use Magento\Framework\App\Filesystem\DirectoryList;

class Save extends \Chirag\Banner\Controller\Adminhtml\Post
{
    public function execute()
    {
        if ($this->getRequest()->getPostValue()) {
            try {
                $model = $this->_objectManager->create('Chirag\Banner\Model\Post');
                $data = $this->getRequest()->getPostValue();
                //print_r($_FILES['imgtemp']['name']); exit("-----");
                if(isset($_FILES['imagepath']['name']) && $_FILES['imagepath']['name'] != '') {
                    try{
                        $uploaderFactory = $this->uploaderFactory->create(['fileId' => 'imagepath']);
                        $uploaderFactory->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
                        $imageAdapter = $this->adapterFactory->create();
                        $uploaderFactory->addValidateCallback('custom_image_upload',$imageAdapter,'validateUploadFile');
                        $uploaderFactory->setAllowRenameFiles(true);
                        $uploaderFactory->setFilesDispersion(true);
                        $mediaDirectory = $this->filesystem->getDirectoryRead($this->directoryList::MEDIA);
                        $destinationPath = $mediaDirectory->getAbsolutePath('chirag/banner');
                        $result = $uploaderFactory->save($destinationPath);
                        if (!$result) {
                            throw new LocalizedException(
                                __('File cannot be saved to path: $1', $destinationPath)
                            );
                        }
                        
                        $imagePath = 'chirag/banner'.$result['file'];
                        $data['imagepath'] = $imagePath;
                    } catch (\Exception $e) {$this->messageManager->addError(__('File is not uploaded !'));
                    }
                }
                if(isset($data['imagepath']['delete']) && $data['imagepath']['delete'] == 1) {
                    $mediaDirectory = $this->filesystem->getDirectoryRead($this->directoryList::MEDIA)->getAbsolutePath();
                    $file = $data['imagepath']['value'];
                    $imgPath = $mediaDirectory.$file;
                    if ($this->_file->isExists($imgPath))  {
                        $this->_file->deleteFile($imgPath);
                    }
                    $data['imagepath'] = NULL;
                }
                if (isset($data['imagepath']['value'])){
                    $data['imagepath'] = $data['imagepath']['value'];
                }
                $inputFilter = new \Zend_Filter_Input(
                    [],
                    [],
                    $data
                );
                $data = $inputFilter->getUnescaped();
                $id = $this->getRequest()->getParam('id');
                if ($id) {
                    $model->load($id);
                    if ($id != $model->getId()) {
                        throw new \Magento\Framework\Exception\LocalizedException(__('The wrong post is specified.'));
                    }
                }
				
				$timezone = $this->_objectManager->create('Magento\Framework\Stdlib\DateTime\TimezoneInterface');
				$fromTz = $timezone->getConfigTimezone();
				$toTz = $timezone->getDefaultTimezone();
				$date = new \DateTime(date('m/d/Y'), new \DateTimeZone($fromTz));
				$date->setTimezone(new \DateTimeZone($toTz));
				$data['date'] = $date->format('m/d/Y H:i:s');
				
				$timezone = $this->_objectManager->create('Magento\Framework\Stdlib\DateTime\DateTime');
				$data['updated_at'] = $timezone->gmtDate();
				
                $model->setData($data);
                $session = $this->_objectManager->get('Magento\Backend\Model\Session');
                $session->setPageData($model->getData());
                $model->save();
                $this->messageManager->addSuccess(__('You saved the banner.'));
                $session->setPageData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('chirag_banner/*/edit', ['id' => $model->getId()]);
                    return;
                }
                $this->_redirect('chirag_banner/*/');
                return;
                } catch (\Magento\Framework\Exception\LocalizedException $e) {
                    $this->messageManager->addError($e->getMessage());
                    $id = (int)$this->getRequest()->getParam('id');
                    if (!empty($id)) {
                        $this->_redirect('chirag_banner/*/edit', ['id' => $id]);
                    } else {
                        $this->_redirect('chirag_banner/*/new');
                    }
                    return;
                } catch (\Exception $e) {
                    $this->messageManager->addError(
                        __('Something went wrong while saving the banner data. Please review the error log.')
                    );
                    $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
                    $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData($data);
                    $this->_redirect('chirag_banner/*/edit', ['id' => $this->getRequest()->getParam('id')]);
                    return;
                }
        }
        $this->_redirect('chirag_banner/*/');
    }
}
