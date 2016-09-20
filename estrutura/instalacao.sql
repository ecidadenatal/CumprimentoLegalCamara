create table plugins.cumprimentotetocamara (
		sequencial integer, 
		bloco integer, 
		anousu integer, 
		estrutural varchar(15), 
		valorprevisao double precision);

create sequence plugins.cumprimentotetocamara_sequencial_seq;

insert into plugins.cumprimentotetocamara 
	select nextval('plugins.cumprimentotetocamara_sequencial_seq'),
		null,
		2017,
		o57_fonte,
		0 
	from orcfontes 
	where o57_anousu = 2017 
		and o57_fonte in ('411100000000000', 
						  '411200000000000', 
						  '412300000010000', 
						  '412102907010000', 
						  '412102907020000', 
						  '412102909000000', 
						  '412102911000000', 
						  '419311100010000', 
						  '419311300010000', 
						  '419319900010000', 
						  '419113800010000', 
						  '419113900010000', 
						  '419114000010000', 
						  '419119800000000', 
						  '419119900010000', 
						  '417210102010000', 
						  '417210105010000', 
						  '417213600010000', 
						  '417220102010000', 
						  '417220104010000');

insert into plugins.cumprimentotetocamara 
	select nextval('plugins.cumprimentotetocamara_sequencial_seq'),
		6,
		2017,
		o57_fonte,
		6000 
	from orcfontes 
	where o57_anousu = 2017 
		and o57_fonte in ('417220101010000');

update plugins.cumprimentotetocamara set bloco = 1 where estrutural in ('411100000000000','411200000000000');
update plugins.cumprimentotetocamara set bloco = 2 where estrutural in ('412300000010000');
update plugins.cumprimentotetocamara set bloco = 3 where estrutural in ('412102907010000','412102907020000','412102909000000','412102911000000');
update plugins.cumprimentotetocamara set bloco = 4 where estrutural in ('419311100010000','419311300010000','419319900010000');
update plugins.cumprimentotetocamara set bloco = 5 where estrutural in ('419113800010000','419113900010000','419114000010000','419119800000000','419119900010000');
update plugins.cumprimentotetocamara set bloco = 6 where estrutural in ('417210102010000','417210105010000','417213600010000','417220102010000','417220104010000');