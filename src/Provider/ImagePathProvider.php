<?php

namespace App\Provider;

use Symfony\Component\HttpFoundation\Request;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class ImagePathProvider
{

    private UploaderHelper $helper;
    private Request $request;

    public function __construct(Request $request, UploaderHelper $helper) {

        $this->helper = $helper;
        $this->request = $request;
    }

    /**
     * @param $image
     * @return string
     */
    public function generatePath($image): ?string {
        if(!$image) {
            return null;
        }
        return $this->request->getSchemeAndHttpHost() . $this->helper->asset($image);
    }

}
