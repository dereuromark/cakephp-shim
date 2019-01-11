<?php
App::uses('Shim', 'Shim.Lib');
App::uses('TranslateBehavior', 'Model/Behavior');
/**
 * TranslateShim behavior
 */
class TranslateShimBehavior extends TranslateBehavior {

/**
 * Bind translation for fields, optionally with hasMany association for
 * fake field.
 *
 * *Note* You should avoid binding translations that overlap existing model properties.
 * This can cause un-expected and un-desirable behavior.
 *
 * @param Model $Model using this behavior of model
 * @param string|array $fields string with field or array(field1, field2=>AssocName, field3)
 * @param bool $reset Leave true to have the fields only modified for the next operation.
 *   if false the field will be added for all future queries.
 * @return bool
 * @throws CakeException when attempting to bind a translating called name. This is not allowed
 *   as it shadows Model::$name.
 */
	public function bindTranslation(Model $Model, $fields, $reset = true) {
		$shim = Configure::read(Shim::BIND_MODEL_METHOD);
		Configure::write(Shim::BIND_MODEL_METHOD, false);
		$result = parent::bindTranslation($Model, $fields, $reset);
		Configure::write(Shim::BIND_MODEL_METHOD, $shim);
		return $result;
	}

}
