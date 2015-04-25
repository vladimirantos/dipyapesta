<?php
use Nette\Callback;
use Nette\Diagnostics\Debugger;
use Nette\Diagnostics\Helpers;

/**
 * Log
 * @param string $message
 */
function l($message) {
    $message = array_map(function ($message) {
        return !is_scalar($message) ? Nette\Utils\Json::encode($message) : $message;
    }, func_get_args());

    Nette\Diagnostics\Debugger::log(implode(', ', $message));
}

/**
 * Bar dump shortcut.
 * @see Nette\Diagnostics\Debugger::barDump
 * @author Filip Procházka <filip@prochazka.su>
 *
 * @param mixed $var
 * @param string $title
 *
 * @return mixed
 */
function b($var, $title = NULL) {
    if (Debugger::$productionMode) {
        return $var;
    }

    $trace = debug_backtrace();
    $traceTitle = (isset($trace[1]['class']) ? htmlspecialchars($trace[1]['class']) . "->" : NULL) .
        htmlspecialchars($trace[1]['function']) . '()' .
        ':' . $trace[0]['line'];

    if (!is_scalar($title) && $title !== NULL) {
        foreach (func_get_args() as $arg) {
            Nette\Diagnostics\Debugger::barDump($arg, $traceTitle);
        }
        return $var;
    }

    return Nette\Diagnostics\Debugger::barDump($var, $title ? : $traceTitle);
}

/**
 * Function prints from where were method/function called
 * @author Filip Procházka <filip@prochazka.su>
 *
 * @param int $level
 * @param bool $return
 * @param bool $fullTrace
 */
function wc($level = 1, $return = FALSE, $fullTrace = FALSE) {
    if (Debugger::$productionMode) {
        return;
    }

    $o = function ($t) {
        return (isset($t->class) ? htmlspecialchars($t->class) . "->" : NULL) . htmlspecialchars($t->function) . '()';
    };
    $f = function ($t) {
        return isset($t->file) ? '(' . Helpers::editorLink($t->file, $t->line) . ')' : NULL;
    };

    $trace = debug_backtrace();
    $target = (object) $trace[$level];
    $caller = (object) $trace[$level + 1];
    $message = NULL;

    if ($fullTrace) {
        array_shift($trace);
        foreach ($trace as $call) {
            $message .= $o((object) $call) . " \n";
        }
    } else {
        $message = $o($target) . " called from " . $o($caller) . $f($caller);
    }

    if ($return) {
        return strip_tags($message);
    }
    echo "<pre class='nette-dump'>" . nl2br($message) . "</pre>";
}