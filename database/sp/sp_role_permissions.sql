DROP PROCEDURE IF EXISTS sp_role_permissions;
GO
DELIMITER //

CREATE PROCEDURE sp_role_permissions(p_Role INTEGER)
BEGIN
    -- SQLINES DEMO *** ded to prevent extra result sets from
    -- SQLINES DEMO *** SELECT statements.
    select a.menu_name    as permission,
           IFNULL((
                      select 'Y' as valuex
                      from permissions as a1
                               left join role_has_permissions as b on a1.id = b.permission_id
                      where b.role_id = p_Role
                        and RIGHT(a1.name, 5) = 'index'
                        and a1.menu_name = a.menu_name
                      group by menu_name
                      limit 1
                  ), 'N') as 'index',
           IFNULL((
                      select 'Y' as valuex
                      from permissions as a1
                               left join role_has_permissions as b on a1.id = b.permission_id
                      where b.role_id = p_Role
                        and RIGHT(a1.name, 5) = 'store'
                        and a1.menu_name = a.menu_name
                      group by menu_name
                      limit 1
                  ), 'N') as store,
           IFNULL((
                      select 'Y' as valuex
                      from permissions as a1
                               left join role_has_permissions as b on a1.id = b.permission_id
                      where b.role_id = p_Role
                        and RIGHT(a1.name, 5) = 'edits'
                        and a1.menu_name = a.menu_name
                      group by menu_name
                      limit 1
                  ), 'N') as edits,
           IFNULL((
                      select 'Y' as valuex
                      from permissions as a1
                               left join role_has_permissions as b on a1.id = b.permission_id
                      where b.role_id = p_Role
                        and RIGHT(a1.name, 5) = 'erase'
                        and a1.menu_name = a.menu_name
                      group by menu_name
                      limit 1
                  ), 'N') as erase,
           a.order_line
    from permissions as a
             left join role_has_permissions as b on a.id = b.permission_id
    where b.role_id = p_Role
    group by a.menu_name, a.order_line

    order by a.order_line;

END;
//

DELIMITER ;


