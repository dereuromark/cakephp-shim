<?php
/**
 * Tree behavior class.
 *
 * Enables a model object to act as a node-based tree.
 *
 * CakePHP :  Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP Project
 * @package       Cake.Model.Behavior
 * @since         CakePHP v 1.2.0.4487
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('TreeBehavior', 'Model');

/**
 * Tree Behavior.
 *
 * Enables a model object to act as a node-based tree. Using Modified Preorder Tree Traversal
 */
class TreeShimBehavior extends TreeBehavior {

	/**
	 * Before save method. Called before all saves
	 *
	 * Overridden to transparently manage setting the lft and rght fields if and only if the parent field is included in the
	 * parameters to be saved. For newly created nodes with NO parent the left and right field values are set directly by
	 * this method bypassing the setParent logic.
	 *
	 * @param Model $Model Model using this behavior
	 * @param array $options Options passed from Model::save().
	 * @return bool true to continue, false to abort the save
	 * @see Model::save()
	 */
	public function beforeSave(Model $Model, $options = []) {
		$tmpDisabled = false;
		if (Configure::read('Shim.deprecateField')) {
			Configure::write('Shim.deprecateField', false);
			$tmpDisabled = true;
		}
		$result = parent::beforeSave($Model, $options);
		if ($tmpDisabled) {
			Configure::write('Shim.deprecateField', true);
		}
		return $result;
	}

	/**
	 * Remove the current node from the tree, and reparent all children up one level.
	 *
	 * If the parameter delete is false, the node will become a new top level node. Otherwise the node will be deleted
	 * after the children are reparented.
	 *
	 * @param Model $Model Model using this behavior
	 * @param int|string|null $id The ID of the record to remove
	 * @param bool $delete whether to delete the node after reparenting children (if any)
	 * @return bool true on success, false on failure
	 * @link http://book.cakephp.org/2.0/en/core-libraries/behaviors/tree.html#TreeBehavior::removeFromTree
	 */
	public function removeFromTree(Model $Model, $id = null, $delete = false) {
		$tmpDisabled = false;
		if (Configure::read('Shim.deprecateSaveField')) {
			Configure::write('Shim.deprecateSaveField', false);
			$tmpDisabled = true;
		}
		$result = parent::removeFromTree($Model, $id, $delete);
		if ($tmpDisabled) {
			Configure::write('Shim.deprecateSaveField', true);
		}
		return $result;
	}

}
