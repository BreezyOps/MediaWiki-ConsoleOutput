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

	public static function console($all_input, array $args, Parser $parser, PPFrame $frame)
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

				$all_input = preg_replace('/>/','&gt;', $all_input);
				$all_input = preg_replace('/</','&lt;', $all_input);

				$all_input = explode("\n", $all_input);
				$string_out = "";
				foreach($all_input as $input) {
				if ( $string_out != "" )
					$string_out .= "\n";
				if ( substr($input, 0, 1) == "#" ) {
					$input = '<span class="code_root">root</span> <span class="code_blue">#</span>' . substr($input, 1);
				} else if ( substr($input, 0, 1) == "$") {
					$input = '<span class="code_user">user</span> <span class="code_blue">$</span>' . substr($input, 1);
				} else if ( substr($input, 0, 1) == "%") {
					// special hostname expansion
					$input = substr($input, 1);
					$other = strpos($input, '%');
					if ( $other != FALSE ) {
						$hostname = substr($input, 0, $other);
						if (substr($hostname, 0, 1) == '$') {
							$hostname = substr($hostname, 1);
							$prompt = '$';
						} else {
							$prompt = '#';
						}
						$input = "<span class=\"code_host\">$hostname</span> <span class=\"code_blue\">$prompt</span>" . substr($input, $other + 1);
					}
				} else if ( substr($input, 0, 1) == "\\") {
					$input = substr($input, 1);
				}

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
				$string_out .= $input;
				}
				
				$out = $parser->getOutput();
#				$out->addModules( 'ext.console.css' );
				return "<pre class=\"code\">" . $string_out . "</pre>";
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
