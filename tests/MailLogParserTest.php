<?php
/**
 * timandes/mail-log-parser
 *
 * @license Apache License Version 2.0
 */

use timandes\parser\MailLogParser;

/**
 * Test suite for MailLogParser
 *
 * @author Timandes White <timands@gmail.com>
 */
class MailLogParserTest extends PHPUnit_Framework_TestCase
{
    public function test1()
    {
        $parser = new MailLogParser();

        $s = "Aug 24 08:22:21 stat1 postfix/smtpd[10254]: warning: unknown[123.4.56.78]: SASL LOGIN authentication failed: authentication failure";
        $r = $parser->parse($s);
        $this->assertEquals('Aug 24 08:22:21', $r->time);
        $this->assertEquals('stat1', $r->serverName);
        $this->assertEquals('smtpd', $r->processName);
        $this->assertEquals(10254, $r->pid);
        $this->assertEquals('warning: unknown[123.4.56.78]: SASL LOGIN authentication failed: authentication failure', $r->Headermessage);

        $s = "Aug 24 17:00:13 stat1 postfix/local[26054]: 06EAF1238A: to=<root@mail.xxx.cn>, relay=local, delay=0.02, delays=0.01/0/0/0.01, dsn=2.0.0, status=sent (delivered to maildir)";
        $r = $parser->parse($s);
        $this->assertEquals('Aug 24 17:00:13', $r->time);
        $this->assertEquals('stat1', $r->serverName);
        $this->assertEquals('local', $r->processName);
        $this->assertEquals(26054, $r->pid);
        $this->assertEquals('06EAF1238A', $r->queueItemId);
        $this->assertEquals('root@mail.xxx.cn', $r->to);
        $this->assertEquals('local', $r->relayHost);
        $this->assertEquals('0.02', $r->delay);
        $this->assertEquals('0.01/0/0/0.01', $r->delays);
        $this->assertEquals('2.0.0', $r->dsn);
        $this->assertEquals('sent', $r->status);
        $this->assertEquals('(delivered to maildir)', $r->Headermessage);

        $s = "Aug 24 17:00:13 stat1 postfix/qmgr[41192]: 06EAF1238A: removed";
        $r = $parser->parse($s);
        $this->assertEquals('Aug 24 17:00:13', $r->time);
        $this->assertEquals('stat1', $r->serverName);
        $this->assertEquals('qmgr', $r->processName);
        $this->assertEquals(41192, $r->pid);
        $this->assertEquals('06EAF1238A', $r->queueItemId);
        $this->assertEquals('removed', $r->Headermessage);

        $s = 'Aug 24 16:59:23 stat1 postfix/smtpd[20464]: NOQUEUE: reject: RCPT from unknown[123.4.56.78]: 550 5.1.1 <buy@mail.xxx.cn>: Recipient address rejected: User unknown in local recipient table; from=<xxx@gmail.com> to=<buy@mail.xxx.cn> proto=ESMTP helo=<mail-io0-f195.google.com>';
        $r = $parser->parse($s);
        $this->assertEquals('Aug 24 16:59:23', $r->time);
        $this->assertEquals('stat1', $r->serverName);
        $this->assertEquals('smtpd', $r->processName);
        $this->assertEquals(20464, $r->pid);
        $this->assertEquals('NOQUEUE: reject: RCPT from unknown[123.4.56.78]: 550 5.1.1 <buy@mail.xxx.cn>: Recipient address rejected: User unknown in local recipient table; from=<xxx@gmail.com> to=<buy@mail.xxx.cn> proto=ESMTP helo=<mail-io0-f195.google.com>', $r->Headermessage);

        $s = 'Aug 24 09:24:11 nbh2 postfix/smtp[6026]: CBF4A7BA8: to=<nrochford@zxx.com>, relay=eu-smtp-inbound-2.xxx.com[123.4.56.78]:25, delay=2.6, delays=0.69/0/0.69/1.2, dsn=5.0.0, status=bounced (host eu-smtp-inbound-2.xxx.com[123.4.56.78] said: 550 csi.xxx.org Poor Reputation Sender. - https://community.xxx.com/docs/DOC-1369#550 (in reply to RCPT TO command))';
        $r = $parser->parse($s);
        $this->assertEquals('Aug 24 09:24:11', $r->time);
        $this->assertEquals('nbh2', $r->serverName);
        $this->assertEquals('smtp', $r->processName);
        $this->assertEquals(6026, $r->pid);
        $this->assertEquals('CBF4A7BA8', $r->queueItemId);
        $this->assertEquals('nrochford@zxx.com', $r->to);
        $this->assertEquals('eu-smtp-inbound-2.xxx.com', $r->relayHost);
        $this->assertEquals('123.4.56.78', $r->relayIp);
        $this->assertEquals(25, $r->relayPort);
        $this->assertEquals('2.6', $r->delay);
        $this->assertEquals('0.69/0/0.69/1.2', $r->delays);
        $this->assertEquals('5.0.0', $r->dsn);
        $this->assertEquals('bounced', $r->status);
        $this->assertEquals('(host eu-smtp-inbound-2.xxx.com[123.4.56.78] said: 550 csi.xxx.org Poor Reputation Sender. - https://community.xxx.com/docs/DOC-1369#550 (in reply to RCPT TO command))', $r->Headermessage);

        $s = 'Aug 28 10:12:10 nbh2 postfix-google/smtp[28345]: 7A7D47BDC: to=<www.xx@gmail.com>, relay=gmail-smtp-in.l.google.com[74.125.133.26]:25, delay=123, delays=0.71/123/0.03/0.02, dsn=5.1.1, status=bounced (host gmail-smtp-in.l.google.com[74.125.133.26] said: 550-5.1.1 The email account that you tried to reach does not exist. Please try 550-5.1.1 double-checking the recipient\'s email address for typos or 550-5.1.1 unnecessary spaces. Learn more at 550 5.1.1  https://support.google.com/mail/?p=NoSuchUser x4si35533wrd.231 - gsmtp (in reply to RCPT TO command))';
        $r = $parser->parse($s);
        $this->assertEquals('Aug 28 10:12:10', $r->time);
        $this->assertEquals('postfix-google', $r->syslogName);
        $this->assertEquals('nbh2', $r->serverName);
        $this->assertEquals('smtp', $r->processName);
        $this->assertEquals(28345, $r->pid);
        $this->assertEquals('7A7D47BDC', $r->queueItemId);
        $this->assertEquals('www.xx@gmail.com', $r->to);
        $this->assertEquals('gmail-smtp-in.l.google.com', $r->relayHost);
        $this->assertEquals('74.125.133.26', $r->relayIp);
        $this->assertEquals(25, $r->relayPort);
        $this->assertEquals('123', $r->delay);
        $this->assertEquals('0.71/123/0.03/0.02', $r->delays);
        $this->assertEquals('5.1.1', $r->dsn);
        $this->assertEquals('bounced', $r->status);
        $this->assertEquals('(host gmail-smtp-in.l.google.com[74.125.133.26] said: 550-5.1.1 The email account that you tried to reach does not exist. Please try 550-5.1.1 double-checking the recipient\'s email address for typos or 550-5.1.1 unnecessary spaces. Learn more at 550 5.1.1  https://support.google.com/mail/?p=NoSuchUser x4si35533wrd.231 - gsmtp (in reply to RCPT TO command))', $r->Headermessage);
    }
}