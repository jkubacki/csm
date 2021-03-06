<?php

/**
 * Database methods for Attribute
 *
 * @author PHP Summer Workshop
 */
class AttributeRepository extends Repository
{

	public function persistRelations(Sheet $sheet, $update = true)
	{
		parent::relations($sheet, $update, 'getAttributes', 'getForSheet', array('value'));
	}

	public function getById($id, $value)
	{
		$baseAttributeRepository = new BaseAttributeRepository($this->db);
		$base = $baseAttributeRepository->getById($id);
		$attribute = new Attribute($base->getId(), $base->getName(), $base->getDescription(), $value);
		return $attribute;
	}

	public function getForSheet(Sheet $sheet)
	{
		$query = "
		SELECT `attribute_id`, `value`
		FROM `sheet_attribute`
		WHERE `sheet_id` = :id
		";
		$handle = $this->db->prepare($query);
		$id = $sheet->getId();
		$handle->bindParam(':id', $id, Database::PARAM_INT);
		$handle->execute();
		$result = $handle->fetchAll(Database::FETCH_ASSOC);
		$attributes = array();
		for ($i = 0; $i < count($result); $i++) {
			$res = $result[$i];
			$attribute = $this->getById($res['attribute_id'], $res['value']);
			$attributes[] = $attribute;
		}
		return $attributes;
	}

}

?>
