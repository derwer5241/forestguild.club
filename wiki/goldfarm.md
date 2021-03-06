---
layout: wiki
title: Фарм золота в WoW BfA. С указанием времени и полученой голды.
author: Этке
description: Гайд по фарму голды в WoW, только проверенные временем способы, никаких фарм-спотов.
---

# Добыча золота.

Фармить голду стало сложнее, фарм-споты (места, где мобы ресаются очень быстро) закрывают с каждым мелким патчем, а играть на что-то надо.

<hr>

<!-- vim-markdown-toc Redcarpet -->

+ [Способы](#способы)
    * [Старые рейды](#старые-рейды)
        - [Статистика по дополнениям](#статистика-по-дополнениям)
        - [Статистика по рейдам](#статистика-по-рейдам)
    * [Профессии](#профессии)
        - [Рыбалка](#рыбалка)
            + [Внешние воды](#внешние-воды)
            + [Внутренние воды](#внутренние-воды)
            + [Итог](#итог)
    * [БоЕшки](#боешки)
    * [Фарм-споты](#фарм-споты)

<!-- vim-markdown-toc -->

<hr>

# Способы

Есть несколько способов для фарма золота, плотно будем разбирать действительно стоящие.

## Старые рейды

В старом контенте очень много голды, да и получить ее не очень сложно. Рассматривать будем рейды до Легиона, так как Легион и выше закрыть на максимальной сложности в соло пока что проблематично и слишком долго для голдфарма.

**Советы**

> Очень важные.

1. Больше всего лута и голды в максимальной сложности рейда. _Например: Цитадель Ледяной Короны героик на 25 человек, Литейная клана Черной Горы эпохальный, Ульдуар на 25 человек героик_
2. Для фарма рейдов лучше взять мобильный класс. _Пример - друид: 2 спринта, походная форма (мгновенный маунт), форма кошки (на 30% больше скорость передвижения), невидимость (инвиз у кошки, можно пропускать кучу ненужного треша), наличие сильных рендж атак (на некоторых боссах вести бой в мили зоне очень неприятно, лучше бить издалека)_
3. Купить [Гильдейский глашатай](https://ru.wowhead.com/item=65364), чтобы можно было вызывать торговца прямо в инст. _[Тундрового мамонта путешественника](https://ru.wowhead.com/spell=61425) не всегда можно призвать внутри рейда)._
4. Поставить аддоны [HandyNotes](http://www.curse.com/addons/wow/handynotes) и [HandyNotes DungeonLocations](https://wow.curseforge.com/projects/handynotes_dungeonlocations), _чтобы прямо на карте видеть входы во все рейды и их статус (зачищен или нет)_
5. Поставить аддон [Scrap (Junk Seller)](https://www.curseforge.com/wow/addons/scrap), _чтобы автоматически продавать низкоуровневые предметы, реагенты и хлам торговцу_

<hr>

**В разработке**. _Статистика по каждому рейду будет добавляться по мере прохождения._

### Статистика по дополнениям

> Суммарное количество золота и времени на все рейды аддона, информацию по каждому рейду в отдельности можно посмотреть ниже
{% assign addons = site.data.goldfarm_raid | map: 'addon' | uniq %}
{:#goldfarm-addons data-sortlist="[[1,1]]"}
| Аддон | Золото | Время, мин | GpM |
|-|-|-|-|
{% for addon in addons -%}

{%- assign addon_gold = 0 | plus: 0 -%}
{%- assign addon_time = 0 | plus: 0 -%}
{%- assign addon_gpm = 0 | plus: 0 -%}
{%- assign addon_raids = site.data.goldfarm_raid | where: 'addon', addon -%}
{%- assign addon_raids_count = addon_raids | size -%}
{%- assign addon_raids_data = 0 | plus: 0 -%}

{%- for raid in addon_raids -%}

{%- assign gold = raid.gold | plus: 0 -%}
{%- assign time = raid.time | plus: 0 -%}
{%- assign addon_gold = addon_gold | plus: gold -%}
{%- assign addon_time = addon_time | plus: time -%}
{%- if gold > 0 -%}{%- assign addon_raids_data = addon_raids_data | plus: 1 -%}{%- endif -%}

{%- endfor -%}
{%- if addon_time == 0 -%}{%- assign addon_time = 1 | plus: 0 -%}{%- endif -%}
{%- assign addon_gpm = addon_gold | divided_by: addon_time | round -%}
| {% if addon_raids_count == addon_raids_data %}**{{ addon }}** _{{ addon_raids_data}}/{{ addon_raids_count }} рейдов_{:.float-right .text-muted}{% else %}{{ addon }} _мало данных {{ addon_raids_data}}/{{ addon_raids_count }} рейдов_{:.float-right .text-muted}{% endif %} | {% if addon_gold > 0 %}{{ addon_gold }}{% endif %} | {% if addon_time != 1 %}{{ addon_time }}{% endif %} | {% if addon_gold > 0 %}{{ addon_gpm }}{% endif %} |
{% endfor %}

{% assign raids_count = site.data.goldfarm_raid | size %}
{% assign raids_data = 0 | plus: 0 %}
{% for raid in site.data.goldfarm_raid %}
{% assign gold = raid.gold | plus: 0 %}
{% if gold > 0 %}
{% assign raids_data = raids_data | plus: 1 %}
{% endif %}
{% endfor %}

### Статистика по рейдам

> На текущий момент собраны данные по **{{ raids_data }}** старым рейдам из **{{ raids_count }}**.

{:#goldfarm-raids data-sortlist="[[3,1]]"}
| Рейд | Аддон | Треш зачищен | Золото | Время, мин | GpM | Персонаж |
|-|-|-|-|-|
{% for raid in site.data.goldfarm_raid -%}
{%- assign time = raid.time | plus: 0 -%}
{%- assign gold = raid.gold | plus: 0 -%}
{%- if time > 0 -%}
{%- assign time = raid.time -%}
{%- else -%}
{%- assign time = 1 | plus: 0 -%}
{%- endif -%}
| {% if gold > 0 %}**{{ raid.name }}**{% else %}{{ raid.name }} _нет данных_{:.float-right .text-muted}{% endif %} | {{ raid.addon }} | {% if raid.trash != '-' %}{{ raid.trash }}{% endif %} | {% if gold > 0 %}{{ raid.gold }}{% endif %}| {% if gold > 0 %}{{ raid.time }}{% endif %} | {% if gold > 0 %}{{ raid.gold | divided_by: time | round }}{% endif %} | {% if gold > 0 %}[{{ raid.runner |default: 'Этке' }}](https://worldofwarcraft.com/ru-ru/character/{% if raid.runner == 'Гадюшница' %}thermaplugg{% else %}galakrond{% endif %}/{{ raid.runner | default: 'Этке' }}){% endif %} |
{% endfor %}

<hr>

## Профессии

> В основном, заработать можно на следующих профессиях: Травничество (продавать траву), Горное дело (продавать руду), Кулинария (продавать пиры и еду), Алхимия (продавать рейдовую химию).

> Потенциально на профах можно заработать очень много, но текущая ситуация очень нестабильна (неделю назад настои/фласки стоили по 800-900 голды за штуку, а сейчас - по 500), поэтому разбирать подробно пока что не будем. Самые профитные профы - травничество (от 10 голды за 1 травинку) и горное дело, дальше - посмотрим.

### Рыбалка

> Автор: [Яжматьепт](https://worldofwarcraft.com/ru-ru/character/galakrond/Яжматьепт).

> **Внимание!** Цены актуальны для сервера EU Галакронд, на других серверах цены могут быть другими.

Чтобы начать зарабатывать на рыбалке, нужно ее изучить у [Молчуньи Тали](https://ru.wowhead.com/npc=122705/). Покупаем у [Шаки](https://ru.wowhead.com/npc=133113/) удочку _(стоит рядом с Тали)_ и отправляемся зарабатывать себе на жизнь. Не забудте перед ловлей надеть удочку (в ячейку для оружия) - дает + к рыбной ловле.

Вылавливать лучше всего [Склизкую макрель](https://ru.wowhead.com/item=152544/) или же [Краснохвостного гольца](https://ru.wowhead.com/item=152549/), они востребование всего.

1. [Склизкая макрель](https://ru.wowhead.com/item=152544/) используется для создания [Болотные фиш-энд-чипс](https://ru.wowhead.com/item=154884/) и добывается во внешних водах (океан)
2. [Краснохвостый голец](https://ru.wowhead.com/item=152549/) нужен для [Пира щедрого капитана](https://ru.wowhead.com/item=156526/) и добывается во внутренних водах (реки и озера).

<hr>

#### Внешние воды

Что бы добыть [Склизкую макрель](https://ru.wowhead.com/item=152544/) идем в [порт в Дазар'Алора, к кораблю Сильваны](https://ru.wowhead.com/maps?data=8499:577634), садимся в уютное место и начинаем рыбачить.
Вместе со [Склизкой макрелью](https://ru.wowhead.com/item=152544/) вам будет попадаться [Песочный плутишка](https://ru.wowhead.com/item=152543/), идет на создание [Камбузного банкета](https://ru.wowhead.com/item=156525/), но не столь востребован.

![Час рыбалки](/assets/img/pages/goldfarm/fishing-sea-1h.jpg){:.float-right}

За 1 час вы выловите примерно **150**шт макрели и **110шт** плутишки.

За **100**шт Склизкой макрели вы сможете продать примерно за **3600**голды, то же самое касается и Краснохвостого гольца - цена примерно равна макрели, но может скакать в пределах 200-300голды за 100 штук.

Продавать на аукционе стоит стаками по **20** или по **50** штук.

Кстати, у вас еще остается бонус в виде Песочного плутишки, примерно **650**голды за стак в 100шт.

> **ProTip!** С 18:00 и до 6:00 по серверному времени у вас есть увеличенный шанс выловить [Полуночный лосось](https://ru.wowhead.com/item=162515/) (нужен для [Камбузный банкет](https://ru.wowhead.com/item=156525/) и для [Пир щедрого капитана](https://ru.wowhead.com/item=156526/)), добывается везде.
За **50**шт вы получите примерно **2800**голды, продавать лучше стаками по **10**шт. А еще есть шанс выловить [Скат Великого моря](https://ru.wowhead.com/item=163131/)

<hr>

#### Внутренние воды

Теперь к [Краснохвостому гольцу](https://ru.wowhead.com/item=152549/) и [Сому Великого моря](https://ru.wowhead.com/item=152547/) (да, он ловится во внутренних водах, не спрашивайте почему). Далеко идти не придется - возвращаемся к [Молчунье Тали](https://ru.wowhead.com/npc=122705/), учителю рыбной ловли и усаживаемся поудобней.

> Сом нужен для [Запеченый сом](https://ru.wowhead.com/item=154889/) (ресурс для сбора на фронты, во время сбора уходит за бешеные деньги на аукционе) и для локального задания (40шт Сома Великого моря)

![Час рыбалки](/assets/img/pages/goldfarm/fishing-river-1h.jpg){:.float-right}

За 1 час вы выловите примерно **135**шт краснохвостого гольца и **130**шт Сомов Великого моря.

За **100**шт Краснохвостого гольца вы получите примерно **3500**голды, за **100**шт Сомов Великого моря можно выручить примерно **1500**голды

> **ProTip!** С 18:00 и до 6:00 по серверному времени у вас есть увеличенный шанс выловить [Полуночный лосось](https://ru.wowhead.com/item=162515/) (нужен для [Камбузный банкет](https://ru.wowhead.com/item=156525/) и для [Пир щедрого капитана](https://ru.wowhead.com/item=156526/)), добывается везде.
За **50**шт вы получите примерно **2800**голды, продавать лучше стаками по **10**шт

<hr>

#### Итог

Я рекомендую ловить рыбу вечером (не забыли про [Полуночный лосось](https://ru.wowhead.com/item=162515/)?) и именно [Склизкую макрель](https://ru.wowhead.com/item=152544/), так как она наиболее востребована среди других обычных рыб, ну и если удастся выловить [Ската Великого моря](https://ru.wowhead.com/item=163131/), то его можно продать за круглую сумму на аукционе.

Всем удачи в ловле правильной рыбы и не заскучайте.

_Яжматьепт (Андрей) для гильдии Ясный Лес @ EU Галакронд_{:.float-right}

<hr>

## БоЕшки

> **БоЕ** (от английской аббревиатуры BoE – bind on equip – "становится персональным при надевании") - вещи с высоким уровнем (325+, 350+, 370+, 400+ илвл), которые можно выбить с мобов и продать на аукционе.

Идем на фарм-спот и молим бога рандома, чтобы вам выпала боеха. Так как сейчас фарм-споты практически не работают, шанс получения боех в открытом мире стремится к нулю.
Есть возможность выбить боешки на островных экспедициях, но не очень высокого уровня. В рейде все гораздо лучше, но шанс минимален, смотри таблицу:

| Зона | Где | Уровень предметов | Сложность |
|-|-|-|-|
| Открытый мир | Фарм-споты | 350-355 | Высокая (см. ниже) |
| Островные экспедиции | На островах | 325-335 | Средняя |
| Рейд | На треше | 350+ (нормал), 370+ (гер) | Средняя |

<hr>

## Фарм-споты

> **Фарм-спот** - место, где мобы возраждаются с огромной скоростью. Убил двух мобов - а они уже сново появились.

С выходом 8.0.1 таких мест было очень много (поищи на youtube "фарм споты bfa"), но Близзард нерфили их один за другим, так что на текущий момент в них нет особого смысла.

В основном, на таких фарм спотах зарабатывали на мусоре (мусор - вендору), ресурсах для профессий (в зависимости от моба, с него могла упасть ткань, мясо и т.д.), БоЕ и маунтах, но сейчас они практически не работают, поэтому более подробно рассматривать мы их не будем


*[GpM]: Gold per Minute - золото в минуту
*[нет лута]: В некоторых рейдах с треша нет добычи вообще, либо ее слишком мало. Нет смысла тут чистить треш
*[нет данных]: Никто еще не замерил результаты. Хочешь помочь? Пронеси этот рейд, записав время (старта и финиша), количество голды (включая продажу всех шмоток из рейда) и зачищен ли треш. А потом зайди в дискорд и скинь эти результаты.
*[мало данных]: Не все рейды этого дополнения были пройдены, результаты не полные. Хочешь помочь? Пронеси эти рейды (см. таблицу ниже), записав время (старта и финиша), количество голды (включая продажу всех шмоток из рейда) и зачищен ли треш. А потом зайди в дискорд и скинь эти результаты.
