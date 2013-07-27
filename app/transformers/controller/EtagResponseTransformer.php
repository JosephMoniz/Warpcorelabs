<?php
namespace app\transformers\controller;
use PlasmaConduit\AbstractHttpResponse;
use PlasmaConduit\headers\response\EtagHeader;
use PlasmaConduit\pipeline\AbstractResponse;
use PlasmaConduit\pipeline\AbstractTransformer;
use PlasmaConduit\pipeline\responses\Ok;

/**
 * Class EtagResponseTransformer
 *
 * @package app\transformers\controller
 */
class EtagResponseTransformer extends AbstractTransformer {

    /**
     * @param $subject
     * @return AbstractResponse
     */
    public function apply($subject) {
        if ($subject instanceof AbstractHttpResponse) {
            return new Ok($this->_generateAndApplyEtag($subject));
        } else {
            return new Ok($subject);
        }
    }

    /**
     * @param AbstractHttpResponse $response
     * @return $this
     */
    public function _generateAndApplyEtag(AbstractHttpResponse $response) {
        $etag = $this->_etagFromResponse($response);
        return $this->_addEtagHeaderToResponse($response, $etag);
    }

    /**
     * @param AbstractHttpResponse $response
     * @return EtagHeader
     */
    private function _etagFromResponse(AbstractHttpResponse $response) {
        return new EtagHeader(sha1($response->view()->render()));
    }

    /**
     * @param AbstractHttpResponse $response
     * @param EtagHeader $header
     * @return $this
     */
    private function _addEtagHeaderToResponse(AbstractHttpResponse $response,
                                              EtagHeader $header)
    {
        return $response->withHeader($header);
    }

}