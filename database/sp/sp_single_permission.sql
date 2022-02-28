DROP PROCEDURE IF EXISTS sp_single_permission;
GO
delimiter //

create PROCEDURE sp_single_permission(p_MenuName NVARCHAR(100))
BEGIN
    select a.menu_name,
           a.app_name,
           a.parent_id,
           a.icon,
           a.route_name,
           CAST(a.has_child as CHAR(5)) as has_child,
           cast(a.has_route as CHAR(5)) as has_route,
           cast(a.is_crud as CHAR(5))   as is_crud,
           a.order_line,
           b.menu_name                  as parent_name,
           (
               SELECT JSON_ARRAYAGG(t1.name)
               FROM roles AS t1
                        LEFT JOIN role_has_permissions AS t2 ON t1.id = t2.role_id
               WHERE t2.permission_id = a.id
           )                            AS role_name,
           (
               select 'Y' as valuex
               from permissions as a1
               where RIGHT(a1.name, 5) = 'store'
                 AND menu_name = a.menu_name
           )                            as store,
           (
               SELECT 'Y' as valuex
               from permissions as a1
               where RIGHT(a1.name, 5) = 'edits'
                 AND menu_name = a.menu_name
           )                            as edits,
           (
               select 'Y' as valuex
               from permissions as a1
               where RIGHT(a1.name, 5) = 'erase'
                 AND menu_name = a.menu_name
           )                            as erase,
           (
               SELECT 'Y' as valuex
               from permissions as a1
               where RIGHT(a1.name, 5) = 'index'
                 AND menu_name = a.menu_name
           )                            as 'index'
    from permissions as a
             left join permissions as b on a.parent_id = b.id

    where a.menu_name = p_MenuName
    order by order_line
    limit 1;
END;
//

delimiter ;



