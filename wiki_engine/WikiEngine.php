<?php

class WikiEngine
{
    const HEADER_MAX_LEVEL = 6;
    const HEADER_PATTERN   = '/^(=+) .+ (=+)$/';

    public function toHtml($input)
    {
        if (!is_string($input)) {
            $error_message = 'Argumrnt is not string type';
            throw new InvalidArgumentException($error_message);
        }

        $replaced_value = array();
        $input_lines = explode(PHP_EOL, $input);
        foreach ($input_lines as $input_line) {
            array_push($replaced_value, $this->convertHeading($input_line));
        }
        $input = implode(PHP_EOL, $replaced_value);

        return $input;
    }

    private function convertHeading($value)
    {
        $header_matched = preg_match(self::HEADER_PATTERN, $value, $matched);

        if ($header_matched) {
            $start = $matched[1];
            $end   = $matched[2];
            $level = strlen($start);

            if ($level <= self::HEADER_MAX_LEVEL && $level === strlen($end)) {
                $body = substr($value, $level + 1, -($level + 1));
                $value = sprintf('<h%d>%s</h%d>', $level, $body, $level);
            }
        }

        return $value;
    }
}
