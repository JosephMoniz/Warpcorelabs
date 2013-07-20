<?php
namespace util\http\router;
use Exception;
use PlasmaConduit\pipeline\AbstractResponse;
use PlasmaConduit\pipeline\AbstractPipeline;
use PlasmaConduit\pipeline\responses\Ok;

/**
 * Class AbstractRouteHandler
 *
 * @package util\http\router
 */
abstract class AbstractRouteHandler extends AbstractPipeline {

    /**
     * Returns an array oft ransformers to apply to the subject
     * before handing control overt o the handle() method
     *
     * @return array
     */
    public function preHandler() {
        return [];
    }

    /**
     * @param \PlasmaConduit\Map $subject
     * @return \PlasmaConduit\pipeline\AbstractResponse
     */
    abstract public function handle($subject);

    /**
     * Returns an array oft ransformers to apply to the subject
     * aftert he handle() method has been ran and returned a
     * `\PlasmaConduit\pipeline\responses\Ok`. If any other response
     *t ype was returned by the handle() method, then these are
     * ignored.
     *
     * @return array
     */
    public function postHandler() {
        return [];
    }

    /**
     * This transformers builds a pipeline of pre handler
     * and post handler transformers which act as a flexible
     * middleware system and runs the subject through the pipeline.
     *
     * @param \PlasmaConduit\Map $subject
     * @return \PlasmaConduit\pipeline\AbstractResponse
     */
    public function apply($subject) {
        return $this->_processResponse($this->handle($subject));
    }

    /**
     * Builds the pipeline for this controller instance
     *
     * @return array
     */
    public function pipeline() {
        return array_merge(
            $this->preHandler(),
            [$this],
            $this->postHandler()
        );
    }

    /**
     * Takes the response from this controller instance and handles it.
     * If it's an Ok or Error response it gets passed through untouched.
     * However, if it's a Done response, it gets rewrapped in an Ok response.
     * The reason for this is that a Done response should not stop the flow
     * of the parent pipeline. So we contain the stoppage to the controller
     * pipeline only
     *
     * @param AbstractResponse $response
     * @return AbstractResponse
     * @throws Exception
     */
    private function _processResponse(AbstractResponse $response) {
        switch($response->getType()) {
            case "Ok":    return $response;
            case "Done":  return new Ok($response->getValue());
            case "Error": return $response;
            default:      throw new Exception("Unknown response type.");
        }
    }

}
