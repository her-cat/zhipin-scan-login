<?php

namespace HerCat\ZhipinScanLogin;


use Closure;
use Exception;
use HerCat\Exceptions\InvalidArgumentException;
use HerCat\Exceptions\LoginFailedException;
use Symfony\Component\Debug\Exception\FatalErrorException;

class ExceptionHandler
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * Exception handler.
     *
     * @var Closure
     */
    protected $handler;

    protected $fatalExceptions = [
        LoginFailedException::class,
    ];

    /**
     * ExceptionHandler constructor.
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Report while exception.
     *
     * @param Exception $e
     *
     * @throws Exception
     */
    public function report(Exception $e)
    {
        if ($this->handler) {
            call_user_func_array($this->handler, [$e]);
        }
    }

    /**
     * Set a exception handler.
     *
     * @param $closure
     *
     * @throws InvalidArgumentException
     */
    public function setHandler($closure)
    {
        if (!is_callable($closure)) {
            throw new InvalidArgumentException('Argument must be callable.');
        }

        $this->handler = $closure;
    }

    /**
     * Convert PHP errors to ErrorException instances.
     *
     * @param $level
     * @param $message
     * @param string $file
     * @param int $line
     *
     * @throws \ErrorException
     */
    public function handleError($level, $message, $file = '', $line = 0)
    {
        if (error_reporting() & $level) {
            throw new \ErrorException($message, 0, $level, $file, $line);
        }
    }

    /**
     * Handle an uncaught exception from application.
     *
     * @param \Throwable $e
     */
    public function handleException(\Throwable $e)
    {
        $this->app->log->error($e->getMessage());

        $this->report($e);

        $this->throwFatalException($e);

        throw $e;
    }

    /**
     * Exception that make application could not work.
     *
     * @param \Throwable $e
     *
     * @throws \Throwable
     */
    private function throwFatalException(\Throwable $e)
    {
        foreach ($this->fatalExceptions as $exception) {
            if ($e instanceof $exception) {
                throw $e;
            }
        }
    }

    /**
     * Handle the PHP shutdown event.
     */
    public function handleShutdown()
    {
        if (!is_null($error = error_get_last()) && $this->isFatal($error['type'])) {
            $this->handleException($this->fatalExceptionFromError($error, 0));
        }

        $this->app->exitObserver->trigger();
    }

    /**
     * Create a new fatal exception from and error array.
     *
     * @param array $error
     *
     * @return FatalErrorException
     */
    public function fatalExceptionFromError(array $error, $traceOffset = null)
    {
        return new FatalErrorException(
            $error['message'], $error['type'], 0, $error['file'], $error['line'], $traceOffset
        );
    }

    /**
     * Determine if the error type is fatal.
     *
     * @param $type
     *
     * @return bool
     */
    public function isFatal($type)
    {
        return in_array($type, [E_COMPILE_ERROR, E_CORE_ERROR, E_ERROR, E_PARSE]);
    }
}