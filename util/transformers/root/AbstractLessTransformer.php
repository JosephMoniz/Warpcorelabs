<?php
namespace util\transformers\root;
use PlasmaConduit\ServiceManager;
use lessc;
use PlasmaConduit\headers\response\ContentTypeHeader;
use PlasmaConduit\HttpRequest;
use PlasmaConduit\Map;
use PlasmaConduit\option\None;
use PlasmaConduit\option\Option;
use PlasmaConduit\option\Some;
use PlasmaConduit\Path;
use PlasmaConduit\pipeline\AbstractTransformer;
use PlasmaConduit\pipeline\responses\Done;
use PlasmaConduit\pipeline\responses\Ok;
use util\http\responses\OkResponse;
use util\render\views\StringView;

/**
 * Class AbstractLessTransformer
 *
 * @package util\transformers\root
 */
abstract class AbstractLessTransformer extends AbstractTransformer {

    /**
     * If the current request is a valid less -> css transformation
     * request, we handle it here and halt the request pipeline.
     * Otherwise, we do nothing to the subject and pass it through
     * the rest of the pipeline for processing.
     *
     * @param \PlasmaConduit\Map $subject
     * @return \PlasmaConduit\pipeline\AbstractResponse
     */
    public function apply($subject) {
        $request  = $this->_getRequestFromSubject($subject);
        $path     = $request->getPath();
        $valid    = $this->_getByValidSuffix($path);
        $exists   = $this->_getExistingFile($valid);
        $compiled = $this->_getCompiledFile($exists);

        return $compiled->toRight($subject)->fold(
            function($subject) {
                return new Ok($subject);
            },
            function($compiled) {
                $view        = new StringView($compiled);
                $response    = new OkResponse($view);
                $contentType = new ContentTypeHeader("text/css");
                return new Done($response->withHeader($contentType));
            }
        );
    }

    /**
     * Returns the path to where the less files can be found
     *
     * @return string
     */
    abstract public function path();

    /**
     * @param Map $subject
     * @return HttpRequest
     */
    private function _getRequestFromSubject(Map $subject) {
        $serviceManager = $subject->get("serviceManager");
        $request = $serviceManager->flatMap(function(ServiceManager $sm) {
            return $sm->get("request");
        });
        return $request->get();
    }

    /**
     * Given a path, returns it as an optional. If the path is a
     * css file then an instance of Some is returned. Otherwise,
     * an instance of None is returned
     *
     * @param string $path
     * @return Option
     */
    private function _getByValidSuffix($path) {
        if (pathinfo($path, PATHINFO_EXTENSION) == "css") {
            return new Some(str_replace(".css", ".less", $path));
        } else {
            return new None();
        }
    }

    /**
     * Given an optional path, return an optional full path
     * if the file exists on disk.
     *
     * @param Option $path
     * @return Option
     */
    private function _getExistingFile(Option $path) {
        return $path->flatMap(function($path) {
            $fullPath = Path::join($this->path(), $path);
            if (file_exists($fullPath)) {
                return new Some($fullPath);
            } else {
                return new None();
            }
        });
    }

    /**
     * Given an optional path, return an optional compiled less file
     *
     * @param Option $path
     * @return Option
     */
    private function _getCompiledFile(Option $path) {
        return $path->map(function($path) {
            $less = new lessc();
            return $less->compileFile($path);
        });
    }

}