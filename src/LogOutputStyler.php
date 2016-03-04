<?php
namespace Consolidation\Log;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\OutputStyle;

/**
 * Styles log output based on format mappings provided in the constructor.
 *
 * Override for greater control.
 */
class LogOutputStyler extends UnstyledLogOutputStyler
{
    const TASK_STYLE_INFO = 'fg=white;bg=cyan;options=bold';
    const TASK_STYLE_SUCCESS = 'fg=white;bg=green;options=bold';
    const TASK_STYLE_WARNING = 'fg=black;bg=yellow;options=bold;';
    const TASK_STYLE_ERROR = 'fg=white;bg=red;options=bold';

    protected $defaultStyles = [
        '*' => 'info',
    ];
    protected $labelStyles = [
        'error' => self::TASK_STYLE_ERROR,
        'warning' => self::TASK_STYLE_WARNING,
        'caution' => self::TASK_STYLE_WARNING,
        'note' => self::TASK_STYLE_INFO,
        'success' => self::TASK_STYLE_SUCCESS,
    ];
    protected $messageStyles = [
        'error' => self::TASK_STYLE_ERROR,
        'warning' => '',
        'caution' => '',
        'note' => '',
        'success' => '',
    ];

    public function __construct($labelStyles = [], $messageStyles = [])
    {
        $this->labelStyles = $labelStyles + $this->labelStyles;
        $this->messageStyles = $messageStyles + $this->messageStyles;
    }

    /**
     * {@inheritdoc}
     */
    public function defaultStyles()
    {
        return $this->defaultStyles;
    }

    /**
     * {@inheritdoc}
     */
    public function style($context)
    {
        $context += ['_style' => []];
        $context['_style'] += $this->defaultStyles();
        foreach ($context as $key => $value) {
            $styleKey = $key;
            if (!isset($context['_style'][$styleKey])) {
                $styleKey = '*';
            }
            if (is_string($value) && isset($context['_style'][$styleKey])) {
                $style = $context['_style'][$styleKey];
                $context[$key] = $this->wrapFormatString($context[$key], $style);
            }
        }
        return $context;
    }

    /**
     * Wrap a string in a format element.
     */
    protected function wrapFormatString($string, $style)
    {
        if ($style) {
            return "<{$style}>$string</>";
        }
        return $string;
    }

    /**
     * Look up the label and message styles for the specified log level,
     * and use the log level as the label for the log message.
     */
    protected function formatMessageByLevel($level, $message, $context)
    {
        $label = $level;
        return $this->formatMessage($label, $message, $context, $this->labelStyles[$level], $this->messageStyles[$level]);
    }

    /**
     * Apply styling with the provided label and message styles.
     */
    protected function formatMessage($label, $message, $context, $labelStyle, $messageStyle = '')
    {
        if (!empty($messageStyle)) {
            $message = $this->wrapFormatString(" $message ", $messageStyle);
        }
        if (!empty($label)) {
            $message = ' ' . $this->wrapFormatString("[$label]", $labelStyle) . ' ' . $message;
        }

        return $message;
    }
}
