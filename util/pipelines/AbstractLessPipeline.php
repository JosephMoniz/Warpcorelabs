<?php
namespace util\pipelines;
use PlasmaConduit\AbstractHttpResponse;
use PlasmaConduit\responses\OkResponse;
use PlasmaConduit\transformers\EndOnResponseTransformer;
use PlasmaConduit\transformers\EtagHitResponseTransformer;
use PlasmaConduit\transformers\EtagResponseTransformer;
use PlasmaConduit\views\StringView;
use PlasmaConduit\headers\response\ContentTypeHeader;
use PlasmaConduit\HttpRequest;
use PlasmaConduit\Map;
use PlasmaConduit\Path;
use PlasmaConduit\pipeline\responses\Done;
use PlasmaConduit\pipeline\responses\Ok;
use PlasmaConduit\option\None;
use PlasmaConduit\option\Option;
use PlasmaConduit\option\Some;
use PlasmaConduit\pipeline\AbstractPipeline;
use PlasmaConduit\ServiceManager;

abstract class AbstractLessPipeline extends AbstractPipeline {

    /**
     * @var bool
     */
    private $_final;

    /**
     * Constructor, takes an optional boolean argument. If it's true
     * then this transformer terminates the pipeline on a less compilation.
     * If false, returns the AbstractHttpResponse in an `Ok` status.
     *
     * @param bool $final
     */
    public function __construct($final = true) {
        $this->_final = $final;
    }

    /**
     * Builds the less pipeline which handles less compilation,
     * Etag cache checking, Etag generation and pipeline termination
     * on compilation hit.
     *
     * @return array
     */
    public function pipeline() {
        return [
            $this,
            new EtagHitResponseTransformer(),
            new EtagResponseTransformer(),
            new EndOnResponseTransformer()
        ];
    }

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
            function($compiled) use($subject) {
                return $this->_serveCompiledResponse($compiled, $subject);
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
     * Given the subject, return the HttpRequest object
     *
     * @param Map $subject
     * @return HttpRequest
     */
    private function _getRequestFromSubject(Map $subject) {
        $sm      = $subject->get("serviceManager");
        $request = $this->_getRequestFromServiceManager($sm);
        return $request->get();
    }

    /**
     * Given an optional service manager, return an optional request
     *
     * @param Option $serviceManager
     * @return Option
     */
    private function _getRequestFromServiceManager(Option $serviceManager) {
        return $serviceManager->flatMap(function(ServiceManager $sm) {
           return $sm->get("request");
        });
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
            $less = new \lessc();
            return $less->compileFile($path);
        });
    }

    /**
     * Serve the compiled response
     *
     * @param string $compiled
     * @param Map $subject
     * @return Done|Ok
     */
    private function _serveCompiledResponse($compiled, Map $subject) {
        $view        = new StringView($compiled);
        $response    = new OkResponse($view);
        $contentType = new ContentTypeHeader("text/css");
        $final       = $response->withHeader($contentType)
                                ->withData($subject);
        return $this->_wrapFinalResponse($final);
    }

    /**
     * Either terminates or continues the flow of the pipeline based
     * on the parameter passed into the constructor
     *
     * @param AbstractHttpResponse $final
     * @return Done|Ok
     */
    private function _wrapFinalResponse(AbstractHttpResponse $final) {
        if ($this->_final) {
            return new Done($final);
        } else {
            return new Ok($final);
        }
    }

}