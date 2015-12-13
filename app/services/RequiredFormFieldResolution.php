<?php

namespace ShoPHP;

trait RequiredFormFieldResolution
{

	/**
	 * @param \Nette\Forms\Controls\BaseControl $control
	 * @param string|null $errorMessage
	 * @param bool|callable $requiring
	 */
	private function resolveRequiring(\Nette\Forms\Controls\BaseControl $control, $errorMessage = null, $requiring = false)
	{
		if ($requiring === true) {
			$control->setRequired($errorMessage !== null ? $errorMessage : true);

		} elseif (is_callable($requiring)) {
			$condition = $requiring($control);
			if (!($condition instanceof \Nette\Forms\Rules)) {
				throw new RequiredFormFieldResolutionException('Requiring call must return instance of \\Nette\\Forms\\Rules.');
			}
			$condition->setRequired($errorMessage !== null ? $errorMessage : true);
		}
	}

}
