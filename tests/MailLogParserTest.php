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

        $s = 'Aug 29 09:52:06 nbh2 opendkim[13237]: BE0C07E22: DKIM-Signature field added (s=default, d=mail.xxx.com)';
        $r = $parser->parse($s);
        $this->assertEquals('Aug 29 09:52:06', $r->time);
        $this->assertEquals('nbh2', $r->serverName);
        $this->assertEquals('opendkim', $r->processName);
        $this->assertEquals(13237, $r->pid);
        $this->assertEquals('BE0C07E22', $r->queueItemId);
        $this->assertEquals('DKIM-Signature field added (s=default, d=mail.xxx.com)', $r->Headermessage);

        $s = 'Aug 29 10:02:07 nbh2 postfix/smtp[10582]: 4ABA87E25: to=<admin@xxx.com>, relay=aspmx.l.google.com[66.102.1.26]:25, delay=1.2, delays=0.72/0.01/0.07/0.4, dsn=2.0.0, status=sent (250 2.0.0 OK 1504000927 u198si1010696wmu.16 - gsmtp)';
        $r = $parser->parse($s);
        $this->assertEquals('Aug 29 10:02:07', $r->time);
        $this->assertEquals('postfix', $r->syslogName);
        $this->assertEquals('nbh2', $r->serverName);
        $this->assertEquals('smtp', $r->processName);
        $this->assertEquals(10582, $r->pid);
        $this->assertEquals('4ABA87E25', $r->queueItemId);
        $this->assertEquals('admin@xxx.com', $r->to);
        $this->assertEquals('aspmx.l.google.com', $r->relayHost);
        $this->assertEquals('66.102.1.26', $r->relayIp);
        $this->assertEquals(25, $r->relayPort);
        $this->assertEquals('1.2', $r->delay);
        $this->assertEquals('0.72/0.01/0.07/0.4', $r->delays);
        $this->assertEquals('2.0.0', $r->dsn);
        $this->assertEquals('sent', $r->status);
        $this->assertEquals('(250 2.0.0 OK 1504000927 u198si1010696wmu.16 - gsmtp)', $r->Headermessage);

        $s = 'Aug 29 14:04:51 nbh2 dovecot: imap-login: Disconnected (disconnected before auth was ready, waited 0 secs): user=<>, rip=12.34.56.78, lip=13.24.57.68, TLS handshaking: SSL_accept() syscall failed: Connection reset by peer, session=<rO8X5eRX3AA0IR9n>';
        $r = $parser->parse($s);
        $this->assertEquals('Aug 29 14:04:51', $r->time);
        $this->assertEquals('nbh2', $r->serverName);
        $this->assertEquals('dovecot', $r->processName);
        $this->assertEquals('imap-login: Disconnected (disconnected before auth was ready, waited 0 secs): user=<>, rip=12.34.56.78, lip=13.24.57.68, TLS handshaking: SSL_accept() syscall failed: Connection reset by peer, session=<rO8X5eRX3AA0IR9n>', $r->Headermessage);

        $s = 'Oct  9 09:23:26 nbh2 postfix/anvil[26210]: statistics: max connection rate 1/60s for (smtp:91.200.12.198) at Oct  9 09:20:04';
        $r = $parser->parse($s);
        $this->assertEquals('Oct  9 09:23:26', $r->time);
        $this->assertEquals('nbh2', $r->serverName);
        $this->assertEquals('anvil', $r->processName);
        $this->assertEquals(26210, $r->pid);
        $this->assertEquals('statistics: max connection rate 1/60s for (smtp:91.200.12.198) at Oct  9 09:20:04', $r->Headermessage);
    }
}