## Модуль, добавляющий новый тип свойств инфоблока "Связанные сущности"

Модуль решает проблему оптимизации загрузки данных по связанным сущностям в списках элеметнов инфоблоков.

Например, стандартный тип свойства "Привязка к элементам в виде списка" для каждого уникального значения связанного 
элемента дёргает CIBlockElement::GetList, что негативно отражается на производительности, особенно если список велик
и содержит много уникальных связанных значений.

Оригинальная идея, лежащая в основе модуля состоит в том, что метод GetPublicViewHTML, отдающий значение свойства,
возвращает не строку, а объект.
Данный объект агрегирует в себя все значения свойства, а данные по ним загружает только в момент, когда текстовое 
значение свойства фактически используется в шаблоне (через магический метод  __toString)

Для использования этого типа свойства, в его настройках необходимо указать полное название класса-хранилища, который
 должен реализовывать интерфейс Storage\StorageInterface
 
Модуль содержит два примера таких хранилищ. В качестве источников данных для них используются
элементы инфоблоков и пользователи  

Цель текущей версии - продемонстрировать подход к групповой выборке данных, поэтому модуль пока что имеет ограниченную 
функциональность. 
Текущая версия модуля не поддерживает множественные свойства, а также имеет примитивный интерфейс редактирования свойства. 
Пока это просто инпут, куда должен вводиться идентификатор связанной сущности. 