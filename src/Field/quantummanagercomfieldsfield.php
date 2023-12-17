<?php namespace Joomla\Plugin\Fields\QuantumManagerComFieldsField\Field;

/**
 * @package    quantummanager
 * @author     Dmitry Tsymbal <cymbal@delo-design.ru>
 * @copyright  Copyright © 2020 Delo Design & NorrNext. All rights reserved.
 * @license    GNU General Public License version 3 or later; see license.txt
 * @link       https://www.norrnext.com
 */

defined('_JEXEC') or die;

use Exception;
use Joomla\CMS\Factory;

/**
 * Class QuantumManagerComFieldsField
 */
class QuantumManagerComFieldsField extends JFormFieldQuantumuploadimage
{

	/**
	 * @var string
	 */
	public $type = 'QuantumManagerComFieldsField';


    public function getInput()
    {
        $db = Factory::getDBO();
        $query = $db->getQuery(true)
            ->select($db->quoteName(array('params', 'fieldparams')))
            ->from('#__fields')
            ->where( 'name=' . $db->quote($this->fieldname));
        $field = $db->setQuery( $query )->loadObject();
        $fieldparams = json_decode($field->fieldparams, JSON_OBJECT_AS_ARRAY);

        try
        {
            $this->__set('dropAreaHidden', $this->getAttribute('dropAreaHidden', true));
            $this->__set('copy', $this->getAttribute('copy', true));
            $this->__set('directory', $fieldparams['path']);

            if($this->copy)
            {

                QuantummanagerLibs::includes([
                    'utils',
                    'notify',
                    'clipboard'
                ]);
            }

            return parent::getInput();
        }
        catch (Exception $e)
        {
            echo $e->getMessage();
        }
    }

}