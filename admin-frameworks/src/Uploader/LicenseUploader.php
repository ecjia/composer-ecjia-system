<?php


namespace Ecjia\System\Frameworks\Uploader;


use RC_Upload;

class LicenseUploader
{
    /**
     * @var \Royalcms\Component\Upload\Uploader\Uploader
     */
    private $uploader;

    public function __construct()
    {
        $this->upload = RC_Upload::uploader('file', array('save_path' => 'data/certificate', 'auto_sub_dirs' => false));
        $this->upload->allowed_type('cer,pem');
        $this->upload->allowed_mime('application/x-x509-ca-cert,application/octet-stream');
    }

    /**
     * @return \Royalcms\Component\Upload\Uploader\Uploader
     */
    public function getUploader()
    {
        return $this->uploader;
    }


}