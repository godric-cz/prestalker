# Meta aneb poznámky k design procesu

## Mergeování

Hned na začátku se objevila jako problém duplikace (sdílení téhož textu mezi postavami). (... problémy duplikace doplnit nebo viz internet ...)

První varianta byla mít dokument `spolecne` plus dokument každé postavy, a to nějak automergeovat. První problém byl, že často není něco sdílené pro všechny, ale pro všechny krom jedné (např. lékařka budí ostatní z hibernace, všichni ostatní mají společný text, jak je lékařka budí).

Dalo by se vrátit k jednoduššímu řešení: Vkládání textů. Tj. někam se vloží symbol (například `(vložit: společný úvod)`) a to se načte ze sdílených textů (kde jsou jednotlivé kousky například oddělené pomocí nadpisů, nebo libovolných jiných symbolů).

Další problém je, že i tak logicky související věci nejsou u sebe (například informace vztahující se k opravě motoru v 2. scéně jsou rozházené na začátku postav, v pokynech pro přípravu na začátku, v materiálech pro scénu a bůhví kde jinde). Je pak težké je měnit (vždycky se na něco zapomene, pokud teda člověk kvůli každé změně nechce číst celou hru znova od začátku do konce).

Lepší řešení by bylo rozřezat hru podle logicky souvisejících věcí (tj. spíš než dokument `marc` mít dokument `oprava motoru` a tyto logické celky potom zmergeovat do herních dokumentů - postav, rekvizit, pokynů, ...).

Pravděpodobně by to bylo lepší, i když i tady jsou nedořešené otázky:

- Náležitost jedné věci do více celků: Např. potřebuji informaci, že je postava nemocná, ve více scénách (jednou protože ji kvůli tomu nechtějí pustit na spacewalk a jednou, protože se projeví její potíže když se budou přesouvat na druhou loď). Podobně se může stát, že dva celky mají protichůdnou informaci (jeden tvrdí, že postava je nemocná, druhý tvrdí - nebo dokonce implicitně předpokládá - že je zdravá).
