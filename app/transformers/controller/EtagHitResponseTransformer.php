<?php
namespace app\transformers\controller;
use PlasmaConduit\AbstractHttpResponse;
use PlasmaConduit\headers\request\IfNoneMatchHeader;
use PlasmaConduit\HttpRequest;
use PlasmaConduit\option\Option;
use PlasmaConduit\pipeline\AbstractTransformer;
use PlasmaConduit\pipeline\responses\Ok;
use PlasmaConduit\responses\CacheHitResponse;
use PlasmaConduit\views\EmptyView;

class EtagHitResponseTransformer extends AbstractTransformer {

    /**
     * @param $subject
     * @return \PlasmaConduit\pipeline\AbstractResponse
     */
    public function apply($subject) {
        if ($subject instanceof AbstractHttpResponse) {
            return $this->_testAndActOnEtag($subject);
        } else {
            return new Ok($subject);
        }
    }

    private function _testAndActOnEtag(AbstractHttpResponse $response) {
        $request   = $this->_getRequestFromResponse($response);
        $requested = $this->_etagFromRequest($request);
        $generated = $this->_etagFromResponse($response);

        if ($this->_isMatch($requested, $generated)) {
            return new Ok($this->_generateHitResponse());
        } else {
            return new Ok($response);
        }
    }

    /**
     * @param AbstractHttpResponse $response
     * @return mixed
     */
    private function _getRequestFromResponse(AbstractHttpResponse $response) {
        return $response->data()->get("serviceManager")->get()->get("request")->get();
    }

    /**
     * @param HttpRequest $request
     * @return \PlasmaConduit\option\Option
     */
    private function _etagFromRequest(HttpRequest $request) {
        $header = $request->getHeader(new IfNoneMatchHeader());
        return $header->map(function(IfNoneMatchHeader $header) {
           return $header->getValue();
        });
    }

    /**
     * @param AbstractHttpResponse $response
     * @return string
     */
    private function _etagFromResponse(AbstractHttpResponse $response) {
        return sha1($response->view()->render());
    }

    /**
     * @param Option $requested
     * @param $generated
     * @return mixed
     */
    private function _isMatch(Option $requested, $generated) {
        return $requested->map(function($requested) use($generated) {
            return $requested == $generated;
        })->getOrElse(false);
    }

    /**
     * Generate a cache hit response
     *
     * @return CacheHitResponse
     */
    private function _generateHitResponse() {
        return new CacheHitResponse(new EmptyView());
    }

}