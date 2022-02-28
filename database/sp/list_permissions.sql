create view list_permissions
as
select distinct a.menu_name,
                a.app_name,
                a.parent_id,
                a.icon,
                a.route_name,
                a.has_child,
                a.has_route,
                a.is_crud,
                a.order_line,
                b.menu_name as parent_name,
                (
                    select 'Y' as valuex
                    from permissions as a1
                    where RIGHT(a1.name, 5) = 'store'
                      AND menu_name = a.menu_name
                )           as store,
                (
                    SELECT 'Y' as valuex
                    from permissions as a1
                    where RIGHT(a1.name, 5) = 'edits'
                      AND menu_name = a.menu_name
                )           as edits,
                (
                    select 'Y' as valuex
                    from permissions as a1
                    where RIGHT(a1.name, 5) = 'erase'
                      AND menu_name = a.menu_name
                )           as erase,
                (
                    SELECT 'Y' as valuex
                    from permissions as a1
                    where RIGHT(a1.name, 5) = 'index'
                      AND menu_name = a.menu_name
                )           as 'index'
from permissions as a
         left join permissions as b on a.parent_id = b.id
