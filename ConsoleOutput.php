<?php

// This code is copyright 2013-2018 Funtoo Solutions, Inc. and is distributed under the GNU General Public
// License version 2, or later.

class ConsoleOutput
{
    public static function onParserFirstCallInit(&$parser)
    {
        $parser->setHook("console", "ConsoleOutput::console");
        return true;
    }

    public static function console($input, array $args, Parser $parser, PPFrame $frame)
    {
        try
        {
            if (count($args))
                {
                    return "<strong class='error'>" .
                           "ConsoleOutput: arguments not supported" .
                           "</strong>";
                }

                # Display < and > as literals, so escape them:

                $input = preg_replace('/>/','&gt;', $input);
                $input = preg_replace('/</','&lt;', $input);

                # http://www.perlmonks.org/?node_id=518444
                # See "Matching a pattern that doesn't include another pattern:

                # input
                $input = preg_replace('/##i##((?:(?!##!i##).)*)##!i##/','<span class="code_input">$1</span>', $input);
                $input = preg_replace('/##i##(.*)/','<span class="code_input">$1</span>', $input);
                # green
                $input = preg_replace('/##g##((?:(?!##!g##).)*)##!g##/','<span class="code_green">$1</span>', $input);
                $input = preg_replace('/##g##(.*)/','<span class="code_green">$1</span>', $input);
                #yellow
                $input = preg_replace('/##y##((?:(?!##!y##).)*)##!y##/','<span class="code_yellow">$1</span>', $input);
                $input = preg_replace('/##y##(.*)/','<span class="code_yellow">$1</span>', $input);
                #blue
                $input = preg_replace('/##bl##((?:(?!##!bl##).)*)##!bl##/','<span class="code_blue">$1</span>', $input);
                $input = preg_replace('/##bl##(.*)/','<span class="code_blue">$1</span>', $input);
                #red
                $input = preg_replace('/##r##((?:(?!##!r##).)*)##!r##/','<span class="code_red">$1</span>', $input);
                $input = preg_replace('/##r##(.*)/','<span class="code_red">$1</span>', $input);
                #cyan
                $input = preg_replace('/##c##((?:(?!##!c##).)*)##!c##/','<span class="code_cyan">$1</span>', $input);
                $input = preg_replace('/##c##(.*)/','<span class="code_cyan">$1</span>', $input);
                #bold
                $input = preg_replace('/##b##((?:(?!##!b##).)*)##!b##/','<b>$1</b>', $input);
                $input = preg_replace('/##b##(.*)/','<b>$1</b>', $input);

				$out = $parser->getOutput();
				$out->addModules( 'ext.console.css' );
                return "<pre class=\"code\">" . $input . "</pre>";
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }

    private function __construct() {}
}

$wgHooks["ParserFirstCallInit"][] = "ConsoleOutput::onParserFirstCallInit";

?>
