DROP PROCEDURE IF EXISTS sp_user_permissions;
GO
/* SQLINES DEMO *** redProcedure [dbo].[sp_role_permissions]    Script Date: 7/31/2021 9:38:51 AM ******/
/* SET ANSI_NULLS ON */

/* SET QUOTED_IDENTIFIER ON */


-- SQLINES LICENSE FOR EVALUATION USE ONLY
DELIMITER //

CREATE PROCEDURE
    sp_user_permissions(p_User INTEGER)
BEGIN
    -- SQLINES LICENSE FOR EVALUATION USE ONLY
    select a.menu_name    as permission,
           IFNULL((
                      select 'Y' as valuex
                      from permissions as a1
                               left join model_has_permissions as b on a1.id = b.permission_id
                      where b.model_id = p_User
                        and RIGHT(a1.name, 5) = 'index'
                        and a1.menu_name = a.menu_name
                      group by menu_name
                      limit 1
                  ), 'N') as 'index',
           IFNULL((
                      select 'Y' as valuex
                      from permissions as a1
                               left join model_has_permissions as b on a1.id = b.permission_id
                      where b.model_id = p_User
                        and RIGHT(a1.name, 5) = 'store'
                        and a1.menu_name = a.menu_name
                      group by menu_name
                      limit 1
                  ), 'N') as store,
           IFNULL((
                      select 'Y' as valuex
                      from permissions as a1
                               left join model_has_permissions as b on a1.id = b.permission_id
                      where b.model_id = p_User
                        and RIGHT(a1.name, 5) = 'edits'
                        and a1.menu_name = a.menu_name
                      group by menu_name
                      limit 1
                  ), 'N') as edits,
           IFNULL((
                      select 'Y' as valuex
                      from permissions as a1
                               left join model_has_permissions as b on a1.id = b.permission_id
                      where b.model_id = p_User
                        and RIGHT(a1.name, 5) = 'erase'
                        and a1.menu_name = a.menu_name
                      group by menu_name
                      limit 1
                  ), 'N') as erase,
           a.order_line
    from permissions as a
             left join model_has_permissions as b on a.id = b.permission_id

    where b.model_id = p_User
    group by a.menu_name, a.order_line
    order by a.order_line;
END;
//

DELIMITER ;

