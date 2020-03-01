<?php
namespace GraphQL\Application\Entity;

use GraphQL\Utils\Utils;

/**
 * Class Proposal
 * Сущность заявления (??).
 * (публичные GraphQL-методы см. в /src/graphql/Application/Type/ProposalType.php)
 * (поля объекта соответствуют полям таблицы, за которой прикреплена сущность - см. метод __getTable())
 *
 * @package GraphQL\Application\Data
 */

class Proposal extends EntityBase
{
    public string $proposal_timestamp;
    public string $id_child;
    public string $id_parent;
    public string $id_association;
    public string $status_admin;
    public string $status_parent;
    public string $status_teacher;


	public function __construct(array $data = null)
	{
		parent::__construct($data);
	}

	/**
	 * Ассоциированная таблица
	 * (таблица должна быть создана в базе данных)
	 *
	 * @return string
	 */
	public function __getTable()
    {
    	return "proposal";
    }
}
