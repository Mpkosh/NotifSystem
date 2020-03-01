<?php
namespace GraphQL\Application\Type;

use GraphQL\Application\AppContext;
use GraphQL\Application\Database\DataSource;
use GraphQL\Application\Data\Proposal;
use GraphQL\Application\Types;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;

class ProposalType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'Proposal',
            'description' => 'Заявления на запись в объединение.',
            'fields' => function() {

	            // Не забывайте писать документацию методов и полей GraphQL, иначе они не будут зарегистрированы.

                return [
                    'id' => Types::id(),
	                'proposal_timestamp' => ['type' => Types::string()],
	                'id_child' => ['type' => Types::string()],
	                'id_parent' => ['type' => Types::string()],
	                'id_association' => ['type' => Types::string()],
	                'status_admin' => ['type' => Types::string()],
	                'status_parent' => ['type' => Types::string()],
                    'status_teacher' => ['type' => Types::string()],
                ];
            },
            'interfaces' => [
                Types::node() //объект, имеющий ID
            ],
            'resolveField' => function($value, $args, $context, ResolveInfo $info) {
                $method = 'resolve' . ucfirst($info->fieldName);
                if (method_exists($this, $method)) {
                    return $this->{$method}($value, $args, $context, $info);
                } else {
                    return $value->{$info->fieldName};
                }
            }
        ];
        parent::__construct($config);
    }

    /*
     * <b>Как добавить свое GraphQL полё</b>
     * Любой видимый для GraphQL в данном классе метод должен:
     *  1) быть публичной функцией,
     *  2) начинаться со слова 'resolve' (см. код на строчках 34-39), последующее слово должно быть написано с большой буквы (например, resolveMyName или resolveMyname и т.п.)
     * Не стоит забывать, что метод должен вернуть какое-нибудь значение для клиента.
     *
     * Пример объявления:
    public function resolvePhoto(User $user, $args)
    {
        return DataSource::getUserPhoto($user->id, $args['size']);
    }
    */

}
