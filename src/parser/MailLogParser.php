<?php
/**
 * timandes/mail-log-parser
 *
 * @license Apache License Version 2.0
 */

namespace timandes\parser;

/**
 * Mail Log Parser
 *
 * @author Timandes White <timands@gmail.com>
 */
class MailLogParser extends \Kassner\LogParser\LogParser
{
    public function __construct($format = null)
    {
        if (!isset($format))
            $format = "%t %v %p%P: %q%T%R%d%D%s%S%{message}i";

        $this->patterns['%t'] = '(?P<time>(?:Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)\s{1,2}\d{1,2}\s\d{2}:\d{2}:\d{2})';
        $this->patterns['%p'] = '((?P<syslogName>[\w-]+)/)?(?P<processName>[\w-]+)';
        $this->patterns['%P'] = '(\[(?P<pid>\d+)\])?';
        $this->patterns['%q'] = '((?P<queueItemId>[0-9A-F]+):\s)?';
        $this->patterns['%T'] = '(to=<(?P<to>[a-zA-Z0-9+_.-]+@[a-z0-9.-]*)>,\s)?';
        $this->patterns['%R'] = '(relay=(?P<relayHost>[a-zA-Z0-9-]+[a-z0-9.-]*)(\[(?P<relayIp>[0-9.]+)\]:(?P<relayPort>\d+))?,\s)?';
        $this->patterns['%d'] = '(delay=(?P<delay>[0-9.]+),\s)?';
        $this->patterns['%D'] = '(delays=(?P<delays>[0-9./]+),\s)?';
        $this->patterns['%s'] = '(dsn=(?P<dsn>[0-9.]+),\s)?';
        $this->patterns['%S'] = '(status=(?P<status>[a-z]+)\s)?';

        parent::__construct($format);
    }
}
