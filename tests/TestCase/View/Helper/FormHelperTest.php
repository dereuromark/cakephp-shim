<?php
declare(strict_types = 1);
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         1.2.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

namespace Shim\Test\TestCase\View\Helper;

use Cake\Core\Configure;
use Cake\Http\ServerRequest;
use Cake\Routing\Router;
use Cake\TestSuite\TestCase;
use Cake\Utility\Security;
use Cake\View\View;
use DateTime;
use Shim\View\Helper\FormHelper;

/**
 * @property \Cake\View\Helper\FormHelper $Form
 * @property \Cake\View\View $View
 */
class FormHelperTest extends TestCase {

	/**
	 * Do not load the fixtures by default
	 *
	 * @var bool
	 */
	public $autoFixtures = false;

	/**
	 * Fixtures to be used
	 *
	 * @var array
	 */
	protected $fixtures = ['core.Articles', 'core.Comments'];

	/**
	 * @var array
	 */
	protected $article = [];

	/**
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();

		Configure::write('Config.language', 'eng');
		Configure::write('App.base', '');
		static::setAppNamespace('Cake\Test\TestCase\View\Helper');

		$request = new ServerRequest([
			'webroot' => '',
			'base' => '',
			'url' => '/articles/add',
			'params' => [
				'controller' => 'articles',
				'action' => 'add',
			],
		]);
		$this->View = new View($request);

		$this->Form = new FormHelper($this->View);

		$this->dateRegex = [
			'daysRegex' => 'preg:/(?:<option value="0?([\d]+)">\\1<\/option>[\r\n]*)*/',
			'monthsRegex' => 'preg:/(?:<option value="[\d]+">[\w]+<\/option>[\r\n]*)*/',
			'yearsRegex' => 'preg:/(?:<option value="([\d]+)">\\1<\/option>[\r\n]*)*/',
			'hoursRegex' => 'preg:/(?:<option value="0?([\d]+)">\\1<\/option>[\r\n]*)*/',
			'minutesRegex' => 'preg:/(?:<option value="([\d]+)">0?\\1<\/option>[\r\n]*)*/',
			'meridianRegex' => 'preg:/(?:<option value="(am|pm)">\\1<\/option>[\r\n]*)*/',
		];

		$this->article = [
			'schema' => [
				'id' => ['type' => 'integer'],
				'author_id' => ['type' => 'integer', 'null' => true],
				'title' => ['type' => 'string', 'null' => true],
				'body' => 'text',
				'published' => ['type' => 'string', 'length' => 1, 'default' => 'N'],
				'_constraints' => ['primary' => ['type' => 'primary', 'columns' => ['id']]],
			],
			'required' => [
				'author_id' => true,
				'title' => true,
			],
		];

		Security::setSalt('foo!');
		Router::connect('/:controller', ['action' => 'index']);
		Router::connect('/:controller/:action/*');
	}

	/**
	 * @return void
	 */
	public function tearDown(): void {
		parent::tearDown();
		unset($this->Form, $this->Controller, $this->View);
		$this->getTableLocator()->clear();
	}

	/**
	 * testControlDatetime method
	 *
	 * Test form->control() with datetime.
	 *
	 * @return void
	 */
	public function testControlDatetime() {
		$this->Form = $this->getMockBuilder('Cake\View\Helper\FormHelper')
			->setMethods(['datetime'])
			->setConstructorArgs([new View()])
			->getMock();
		$this->Form->expects($this->once())->method('datetime')
			->with('prueba', [
				'type' => 'datetime',
				'timeFormat' => 24,
				'minYear' => 2008,
				'maxYear' => 2011,
				'interval' => 15,
				'options' => null,
				'id' => 'prueba',
				'required' => null,
				'templateVars' => [],
			])
			->will($this->returnValue('This is it!'));
		$result = $this->Form->control('prueba', [
			'type' => 'datetime', 'timeFormat' => 24, 'minYear' => 2008,
			'maxYear' => 2011, 'interval' => 15,
		]);
		$expected = [
			'div' => ['class' => 'input datetime'],
			'label' => ['for' => 'prueba'],
			'Prueba',
			'/label',
			'This is it!',
			'/div',
		];
		$this->assertHtml($expected, $result);
	}

	/**
	 * testControlDatetimeIdPrefix method
	 *
	 * Test form->control() with datetime with id prefix.
	 *
	 * @return void
	 */
	public function testControlDatetimeIdPrefix() {
		$this->Form = $this->getMockBuilder('Cake\View\Helper\FormHelper')
			->setMethods(['datetime'])
			->setConstructorArgs([new View()])
			->getMock();

		$this->Form->create(null, ['idPrefix' => 'prefix']);

		$this->Form->expects($this->once())->method('datetime')
			->with('prueba', [
				'type' => 'datetime',
				'timeFormat' => 24,
				'minYear' => 2008,
				'maxYear' => 2011,
				'interval' => 15,
				'options' => null,
				'id' => 'prefix-prueba',
				'required' => null,
				'templateVars' => [],
			])
			->will($this->returnValue('This is it!'));
		$result = $this->Form->control('prueba', [
			'type' => 'datetime', 'timeFormat' => 24, 'minYear' => 2008,
			'maxYear' => 2011, 'interval' => 15,
		]);
		$expected = [
			'div' => ['class' => 'input datetime'],
			'label' => ['for' => 'prefix-prueba'],
			'Prueba',
			'/label',
			'This is it!',
			'/div',
		];
		$this->assertHtml($expected, $result);
	}

	/**
	 * testTime method
	 *
	 * Test the time type.
	 *
	 * @return void
	 */
	public function testTime() {
		$result = $this->Form->time('start_time', [
			'timeFormat' => 12,
			'interval' => 5,
			'value' => ['hour' => '4', 'minute' => '30', 'meridian' => 'pm'],
		]);
		$this->assertStringContainsString('<option value="04" selected="selected">4</option>', $result);
		$this->assertStringContainsString('<option value="30" selected="selected">30</option>', $result);
		$this->assertStringContainsString('<option value="pm" selected="selected">pm</option>', $result);
		$this->assertStringNotContainsString('year', $result);
		$this->assertStringNotContainsString('month', $result);
		$this->assertStringNotContainsString('day', $result);

		$result = $this->Form->time('start_time', [
			'timeFormat' => 12,
			'interval' => 5,
			'value' => '2014-03-08 16:30:00',
		]);
		$this->assertStringContainsString('<option value="04" selected="selected">4</option>', $result);
		$this->assertStringContainsString('<option value="30" selected="selected">30</option>', $result);
		$this->assertStringContainsString('<option value="pm" selected="selected">pm</option>', $result);
		$this->assertStringNotContainsString('year', $result);
		$this->assertStringNotContainsString('month', $result);
		$this->assertStringNotContainsString('day', $result);
	}

	/**
	 * testTimeFormat24NoMeridian method
	 *
	 * Ensure that timeFormat=24 has no merdian.
	 *
	 * @return void
	 */
	public function testTimeFormat24NoMeridian() {
		$result = $this->Form->time('start_time', [
			'timeFormat' => 24,
			'interval' => 5,
			'value' => '2014-03-08 16:30:00',
		]);
		$this->assertStringContainsString('<option value="16" selected="selected">16</option>', $result);
		$this->assertStringContainsString('<option value="30" selected="selected">30</option>', $result);
		$this->assertStringNotContainsString('meridian', $result);
		$this->assertStringNotContainsString('pm', $result);
		$this->assertStringNotContainsString('year', $result);
		$this->assertStringNotContainsString('month', $result);
		$this->assertStringNotContainsString('day', $result);
	}

	/**
	 * testDate method
	 *
	 * Test the date type.
	 *
	 * @return void
	 */
	public function testDate() {
		$result = $this->Form->date('start_day', [
			'value' => ['year' => '2014', 'month' => '03', 'day' => '08'],
		]);
		$this->assertStringContainsString('<option value="2014" selected="selected">2014</option>', $result);
		$this->assertStringContainsString('<option value="03" selected="selected">March</option>', $result);
		$this->assertStringContainsString('<option value="08" selected="selected">8</option>', $result);
		$this->assertStringNotContainsString('hour', $result);
		$this->assertStringNotContainsString('minute', $result);
		$this->assertStringNotContainsString('second', $result);
		$this->assertStringNotContainsString('meridian', $result);
	}

	/**
	 * testDateTime method
	 *
	 * Test generation of date/time select elements.
	 *
	 * @return void
	 */
	public function testDateTime() {
		extract($this->dateRegex);

		$result = $this->Form->dateTime('Contact.date', ['default' => true]);
		$now = strtotime('now');
		$expected = [
			['select' => ['name' => 'Contact[date][year]']],
			['option' => ['value' => '']],
			'/option',
			$yearsRegex,
			['option' => ['value' => date('Y', $now), 'selected' => 'selected']],
			date('Y', $now),
			'/option',
			'*/select',

			['select' => ['name' => 'Contact[date][month]']],
			['option' => ['value' => '']],
			'/option',
			$monthsRegex,
			['option' => ['value' => date('m', $now), 'selected' => 'selected']],
			date('F', $now),
			'/option',
			'*/select',

			['select' => ['name' => 'Contact[date][day]']],
			['option' => ['value' => '']],
			'/option',
			$daysRegex,
			['option' => ['value' => date('d', $now), 'selected' => 'selected']],
			date('j', $now),
			'/option',
			'*/select',

			['select' => ['name' => 'Contact[date][hour]']],
			['option' => ['value' => '']],
			'/option',
			$hoursRegex,
			['option' => ['value' => date('H', $now), 'selected' => 'selected']],
			date('G', $now),
			'/option',
			'*/select',

			['select' => ['name' => 'Contact[date][minute]']],
			['option' => ['value' => '']],
			'/option',
			$minutesRegex,
			['option' => ['value' => date('i', $now), 'selected' => 'selected']],
			date('i', $now),
			'/option',
			'*/select',
		];
		$this->assertHtml($expected, $result);

		// Empty=>false implies Default=>true, as selecting the "first" dropdown value is useless
		$result = $this->Form->dateTime('Contact.date', ['empty' => false]);
		$now = strtotime('now');
		$expected = [
			['select' => ['name' => 'Contact[date][year]']],
			$yearsRegex,
			['option' => ['value' => date('Y', $now), 'selected' => 'selected']],
			date('Y', $now),
			'/option',
			'*/select',

			['select' => ['name' => 'Contact[date][month]']],
			$monthsRegex,
			['option' => ['value' => date('m', $now), 'selected' => 'selected']],
			date('F', $now),
			'/option',
			'*/select',

			['select' => ['name' => 'Contact[date][day]']],
			$daysRegex,
			['option' => ['value' => date('d', $now), 'selected' => 'selected']],
			date('j', $now),
			'/option',
			'*/select',

			['select' => ['name' => 'Contact[date][hour]']],
			$hoursRegex,
			['option' => ['value' => date('H', $now), 'selected' => 'selected']],
			date('G', $now),
			'/option',
			'*/select',

			['select' => ['name' => 'Contact[date][minute]']],
			$minutesRegex,
			['option' => ['value' => date('i', $now), 'selected' => 'selected']],
			date('i', $now),
			'/option',
			'*/select',
		];
		$this->assertHtml($expected, $result);
	}

	/**
	 * testDatetimeEmpty method
	 *
	 * Test empty defaulting to true for datetime.
	 *
	 * @return void
	 */
	public function testDatetimeEmpty() {
		extract($this->dateRegex);

		$result = $this->Form->dateTime('Contact.date', [
			'timeFormat' => 12,
			'empty' => true,
			'default' => true,
		]);
		$expected = [
			['select' => ['name' => 'Contact[date][year]']],
			$yearsRegex,
			['option' => ['value' => '']],
			'/option',
			'*/select',

			['select' => ['name' => 'Contact[date][month]']],
			$monthsRegex,
			['option' => ['value' => '']],
			'/option',
			'*/select',

			['select' => ['name' => 'Contact[date][day]']],
			$daysRegex,
			['option' => ['value' => '']],
			'/option',
			'*/select',

			['select' => ['name' => 'Contact[date][hour]']],
			$hoursRegex,
			['option' => ['value' => '']],
			'/option',
			'*/select',

			['select' => ['name' => 'Contact[date][minute]']],
			$minutesRegex,
			['option' => ['value' => '']],
			'/option',
			'*/select',

			['select' => ['name' => 'Contact[date][meridian]']],
			$meridianRegex,
			['option' => ['value' => '']],
			'/option',
			'*/select',
		];
		$this->assertHtml($expected, $result);
		$this->assertNotRegExp('/<option[^<>]+value=""[^<>]+selected="selected"[^>]*>/', $result);
	}

	/**
	 * testDatetimeMinuteInterval method
	 *
	 * Test datetime with interval option.
	 *
	 * @return void
	 */
	public function testDatetimeMinuteInterval() {
		extract($this->dateRegex);

		$result = $this->Form->dateTime('Contact.date', [
			'interval' => 5,
			'value' => '',
		]);
		$expected = [
			['select' => ['name' => 'Contact[date][year]']],
			$yearsRegex,
			['option' => ['selected' => 'selected', 'value' => '']],
			'/option',
			'*/select',

			['select' => ['name' => 'Contact[date][month]']],
			$monthsRegex,
			['option' => ['selected' => 'selected', 'value' => '']],
			'/option',
			'*/select',

			['select' => ['name' => 'Contact[date][day]']],
			$daysRegex,
			['option' => ['selected' => 'selected', 'value' => '']],
			'/option',
			'*/select',

			['select' => ['name' => 'Contact[date][hour]']],
			$hoursRegex,
			['option' => ['selected' => 'selected', 'value' => '']],
			'/option',
			'*/select',

			['select' => ['name' => 'Contact[date][minute]']],
			$minutesRegex,
			['option' => ['selected' => 'selected', 'value' => '']],
			'/option',
			['option' => ['value' => '00']],
			'00',
			'/option',
			['option' => ['value' => '05']],
			'05',
			'/option',
			['option' => ['value' => '10']],
			'10',
			'/option',
			'*/select',
		];
		$this->assertHtml($expected, $result);
	}

	/**
	 * testDateTimeRounding method
	 *
	 * Test dateTime with rounding.
	 *
	 * @return void
	 */
	public function testDateTimeRounding() {
		$this->View->setRequest($this->View->getRequest()->withData('Contact', [
			'date' => [
				'day' => '13',
				'month' => '12',
				'year' => '2010',
				'hour' => '04',
				'minute' => '19',
				'meridian' => 'AM',
			],
		]));

		$result = $this->Form->dateTime('Contact.date', ['interval' => 15]);
		$this->assertTextContains('<option value="15" selected="selected">15</option>', $result);

		$result = $this->Form->dateTime('Contact.date', ['interval' => 15, 'round' => 'up']);
		$this->assertTextContains('<option value="30" selected="selected">30</option>', $result);

		$result = $this->Form->dateTime('Contact.date', ['interval' => 5, 'round' => 'down']);
		$this->assertTextContains('<option value="15" selected="selected">15</option>', $result);
	}

	/**
	 * testDatetimeWithDefault method
	 *
	 * Test that datetime() and default values work.
	 *
	 * @return void
	 */
	public function testDatetimeWithDefault() {
		$result = $this->Form->dateTime('Contact.updated', ['value' => '2009-06-01 11:15:30']);
		$this->assertRegExp('/<option[^<>]+value="2009"[^<>]+selected="selected"[^>]*>2009<\/option>/', $result);
		$this->assertRegExp('/<option[^<>]+value="01"[^<>]+selected="selected"[^>]*>1<\/option>/', $result);
		$this->assertRegExp('/<option[^<>]+value="06"[^<>]+selected="selected"[^>]*>June<\/option>/', $result);

		$result = $this->Form->dateTime('Contact.updated', [
			'default' => '2009-06-01 11:15:30',
		]);
		$this->assertRegExp('/<option[^<>]+value="2009"[^<>]+selected="selected"[^>]*>2009<\/option>/', $result);
		$this->assertRegExp('/<option[^<>]+value="01"[^<>]+selected="selected"[^>]*>1<\/option>/', $result);
		$this->assertRegExp('/<option[^<>]+value="06"[^<>]+selected="selected"[^>]*>June<\/option>/', $result);
	}

	/**
	 * testDateTimeAllZeros method
	 *
	 * Test datetime() with all zeros.
	 *
	 * @return void
	 */
	public function testDateTimeAllZeros() {
		$result = $this->Form->dateTime('Contact.date', [
			'timeFormat' => false,
			'empty' => ['day' => '-', 'month' => '-', 'year' => '-'],
			'value' => '0000-00-00',
		]);

		$this->assertRegExp('/<option value="">-<\/option>/', $result);
		$this->assertNotRegExp('/<option value="0" selected="selected">0<\/option>/', $result);
	}

	/**
	 * testDateTimeEmptyAsArray method
	 *
	 * @return void
	 */
	public function testDateTimeEmptyAsArray() {
		$result = $this->Form->dateTime('Contact.date', [
			'empty' => [
				'day' => 'DAY',
				'month' => 'MONTH',
				'year' => 'YEAR',
				'hour' => 'HOUR',
				'minute' => 'MINUTE',
				'meridian' => false,
			],
			'default' => true,
		]);

		$this->assertRegExp('/<option value="">DAY<\/option>/', $result);
		$this->assertRegExp('/<option value="">MONTH<\/option>/', $result);
		$this->assertRegExp('/<option value="">YEAR<\/option>/', $result);
		$this->assertRegExp('/<option value="">HOUR<\/option>/', $result);
		$this->assertRegExp('/<option value="">MINUTE<\/option>/', $result);
		$this->assertNotRegExp('/<option value=""><\/option>/', $result);

		$result = $this->Form->dateTime('Contact.date', [
			'empty' => ['day' => 'DAY', 'month' => 'MONTH', 'year' => 'YEAR'],
			'default' => true,
		]);

		$this->assertRegExp('/<option value="">DAY<\/option>/', $result);
		$this->assertRegExp('/<option value="">MONTH<\/option>/', $result);
		$this->assertRegExp('/<option value="">YEAR<\/option>/', $result);
	}

	/**
	 * testFormDateTimeMulti method
	 *
	 * Test multiple datetime element generation.
	 *
	 * @return void
	 */
	public function testFormDateTimeMulti() {
		extract($this->dateRegex);

		$result = $this->Form->dateTime('Contact.1.updated');
		$this->assertStringContainsString('Contact[1][updated][month]', $result);
		$this->assertStringContainsString('Contact[1][updated][day]', $result);
		$this->assertStringContainsString('Contact[1][updated][year]', $result);
		$this->assertStringContainsString('Contact[1][updated][hour]', $result);
		$this->assertStringContainsString('Contact[1][updated][minute]', $result);
	}

	/**
	 * testDateTimeLabelIdMatchesFirstControl method
	 *
	 * When changing the date format, the label should always focus the first select box when
	 * clicked.
	 *
	 * @return void
	 */
	public function testDateTimeLabelIdMatchesFirstControl() {
		$result = $this->Form->control('Model.date', ['type' => 'date']);
		$this->assertStringContainsString('<label>Date</label>', $result);

		$result = $this->Form->control('Model.date', ['type' => 'date', 'dateFormat' => 'DMY']);
		$this->assertStringContainsString('<label>Date</label>', $result);

		$result = $this->Form->control('Model.date', ['type' => 'date', 'dateFormat' => 'YMD']);
		$this->assertStringContainsString('<label>Date</label>', $result);
	}

	/**
	 * testDateTimeSecondOptions method
	 *
	 * Test datetime second=true.
	 *
	 * @return void
	 */
	public function testDateTimeSecondOptions() {
		$result = $this->Form->dateTime('updated', ['second' => true]);
		$this->assertStringContainsString('updated[second]', $result, 'Should have seconds');

		$result = $this->Form->dateTime('updated', ['second' => []]);
		$this->assertStringContainsString('updated[second]', $result, 'Should have seconds');

		$result = $this->Form->dateTime('updated', ['second' => null]);
		$this->assertStringNotContainsString('updated[second]', $result, 'Should not have seconds');

		$result = $this->Form->dateTime('updated', ['second' => false]);
		$this->assertStringNotContainsString('updated[second]', $result, 'Should not have seconds');
	}

	/**
	 * testMonth method
	 *
	 * Test generation of a month input.
	 *
	 * @return void
	 */
	public function testMonth() {
		$result = $this->Form->month('Model.field', ['value' => '']);
		$expected = [
			['select' => ['name' => 'Model[field][month]']],
			['option' => ['value' => '', 'selected' => 'selected']],
			'/option',
			['option' => ['value' => '01']],
			date('F', strtotime('2008-01-01 00:00:00')),
			'/option',
			['option' => ['value' => '02']],
			date('F', strtotime('2008-02-01 00:00:00')),
			'/option',
			'*/select',
		];
		$this->assertHtml($expected, $result);

		$result = $this->Form->month('Model.field', ['empty' => true, 'value' => '']);
		$expected = [
			['select' => ['name' => 'Model[field][month]']],
			['option' => ['selected' => 'selected', 'value' => '']],
			'/option',
			['option' => ['value' => '01']],
			date('F', strtotime('2008-01-01 00:00:00')),
			'/option',
			['option' => ['value' => '02']],
			date('F', strtotime('2008-02-01 00:00:00')),
			'/option',
			'*/select',
		];
		$this->assertHtml($expected, $result);

		$result = $this->Form->month('Model.field', ['value' => '', 'monthNames' => false]);
		$expected = [
			['select' => ['name' => 'Model[field][month]']],
			['option' => ['selected' => 'selected', 'value' => '']],
			'/option',
			['option' => ['value' => '01']],
			'1',
			'/option',
			['option' => ['value' => '02']],
			'2',
			'/option',
			'*/select',
		];
		$this->assertHtml($expected, $result);

		$monthNames = [
			'01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'May', '06' => 'Jun',
			'07' => 'Jul', '08' => 'Aug', '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec',
		];
		$result = $this->Form->month('Model.field', ['value' => '1', 'monthNames' => $monthNames]);
		$expected = [
			['select' => ['name' => 'Model[field][month]']],
			['option' => ['value' => '']],
			'/option',
			['option' => ['value' => '01', 'selected' => 'selected']],
			'Jan',
			'/option',
			['option' => ['value' => '02']],
			'Feb',
			'/option',
			'*/select',
		];
		$this->assertHtml($expected, $result);

		$this->View->setRequest(
			$this->View->getRequest()->withData('Project.release', '2050-02-10')
		);
		$this->Form->create();
		$result = $this->Form->month('Project.release');

		$expected = [
			['select' => ['name' => 'Project[release][month]']],
			['option' => ['value' => '']],
			'/option',
			['option' => ['value' => '01']],
			'January',
			'/option',
			['option' => ['value' => '02', 'selected' => 'selected']],
			'February',
			'/option',
			'*/select',
		];
		$this->assertHtml($expected, $result);

		$result = $this->Form->month('Contact.published', [
			'empty' => 'Published on',
		]);
		$this->assertStringContainsString('Published on', $result);
	}

	/**
	 * testDay method
	 *
	 * Test generation of a day input.
	 *
	 * @return void
	 */
	public function testDay() {
		extract($this->dateRegex);

		$result = $this->Form->day('Model.field', ['value' => '', 'class' => 'form-control']);
		$expected = [
			['select' => ['name' => 'Model[field][day]', 'class' => 'form-control']],
			['option' => ['selected' => 'selected', 'value' => '']],
			'/option',
			['option' => ['value' => '01']],
			'1',
			'/option',
			['option' => ['value' => '02']],
			'2',
			'/option',
			$daysRegex,
			'/select',
		];
		$this->assertHtml($expected, $result);

		$this->View->setRequest(
			$this->View->getRequest()->withData('Model.field', '2006-10-10 23:12:32')
		);
		$this->Form->create();
		$result = $this->Form->day('Model.field');
		$expected = [
			['select' => ['name' => 'Model[field][day]']],
			['option' => ['value' => '']],
			'/option',
			['option' => ['value' => '01']],
			'1',
			'/option',
			['option' => ['value' => '02']],
			'2',
			'/option',
			$daysRegex,
			['option' => ['value' => '10', 'selected' => 'selected']],
			'10',
			'/option',
			$daysRegex,
			'/select',
		];
		$this->assertHtml($expected, $result);

		$this->View->setRequest($this->View->getRequest()->withData('Model.field', ''));
		$this->Form->create();
		$result = $this->Form->day('Model.field', ['value' => '10']);
		$expected = [
			['select' => ['name' => 'Model[field][day]']],
			['option' => ['value' => '']],
			'/option',
			['option' => ['value' => '01']],
			'1',
			'/option',
			['option' => ['value' => '02']],
			'2',
			'/option',
			$daysRegex,
			['option' => ['value' => '10', 'selected' => 'selected']],
			'10',
			'/option',
			$daysRegex,
			'/select',
		];
		$this->assertHtml($expected, $result);

		$this->View->setRequest(
			$this->View->getRequest()->withData('Project.release', '2050-10-10')
		);
		$this->Form->create();
		$result = $this->Form->day('Project.release');

		$expected = [
			['select' => ['name' => 'Project[release][day]']],
			['option' => ['value' => '']],
			'/option',
			['option' => ['value' => '01']],
			'1',
			'/option',
			['option' => ['value' => '02']],
			'2',
			'/option',
			$daysRegex,
			['option' => ['value' => '10', 'selected' => 'selected']],
			'10',
			'/option',
			$daysRegex,
			'/select',
		];
		$this->assertHtml($expected, $result);

		$result = $this->Form->day('Contact.published', [
			'empty' => 'Published on',
		]);
		$this->assertStringContainsString('Published on', $result);
	}

	/**
	 * testMinute method
	 *
	 * Test generation of a minute input.
	 *
	 * @return void
	 */
	public function testMinute() {
		extract($this->dateRegex);

		$result = $this->Form->minute('Model.field', ['value' => '']);
		$expected = [
			['select' => ['name' => 'Model[field][minute]']],
			['option' => ['selected' => 'selected', 'value' => '']],
			'/option',
			['option' => ['value' => '00']],
			'00',
			'/option',
			['option' => ['value' => '01']],
			'01',
			'/option',
			['option' => ['value' => '02']],
			'02',
			'/option',
			$minutesRegex,
			'/select',
		];
		$this->assertHtml($expected, $result);

		$this->View->setRequest(
			$this->View->getRequest()->withData('Model.field', '2006-10-10 00:12:32')
		);
		$this->Form->create();
		$result = $this->Form->minute('Model.field');
		$expected = [
			['select' => ['name' => 'Model[field][minute]']],
			['option' => ['value' => '']],
			'/option',
			['option' => ['value' => '00']],
			'00',
			'/option',
			['option' => ['value' => '01']],
			'01',
			'/option',
			['option' => ['value' => '02']],
			'02',
			'/option',
			$minutesRegex,
			['option' => ['value' => '12', 'selected' => 'selected']],
			'12',
			'/option',
			$minutesRegex,
			'/select',
		];
		$this->assertHtml($expected, $result);

		$this->View->setRequest($this->View->getRequest()->withData('Model.field', ''));
		$this->Form->create();
		$result = $this->Form->minute('Model.field', ['interval' => 5]);
		$expected = [
			['select' => ['name' => 'Model[field][minute]']],
			['option' => ['selected' => 'selected', 'value' => '']],
			'/option',
			['option' => ['value' => '00']],
			'00',
			'/option',
			['option' => ['value' => '05']],
			'05',
			'/option',
			['option' => ['value' => '10']],
			'10',
			'/option',
			$minutesRegex,
			'/select',
		];
		$this->assertHtml($expected, $result);

		$this->View->setRequest(
			$this->View->getRequest()->withData('Model.field', '2006-10-10 00:10:32')
		);
		$this->Form->create();
		$result = $this->Form->minute('Model.field', ['interval' => 5]);
		$expected = [
			['select' => ['name' => 'Model[field][minute]']],
			['option' => ['value' => '']],
			'/option',
			['option' => ['value' => '00']],
			'00',
			'/option',
			['option' => ['value' => '05']],
			'05',
			'/option',
			['option' => ['value' => '10', 'selected' => 'selected']],
			'10',
			'/option',
			$minutesRegex,
			'/select',
		];
		$this->assertHtml($expected, $result);
	}

	/**
	 * testMeridian method
	 *
	 * Test generating an input for the meridian.
	 *
	 * @return void
	 */
	public function testMeridian() {
		extract($this->dateRegex);

		$now = new DateTime();
		$result = $this->Form->meridian('Model.field', ['value' => 'am']);
		$expected = [
			['select' => ['name' => 'Model[field][meridian]']],
			['option' => ['value' => '']],
			'/option',
			$meridianRegex,
			['option' => ['value' => $now->format('a'), 'selected' => 'selected']],
			$now->format('a'),
			'/option',
			'*/select',
		];
		$this->assertHtml($expected, $result);
	}

	/**
	 * testHour method
	 *
	 * Test generation of an hour input.
	 *
	 * @return void
	 */
	public function testHour() {
		extract($this->dateRegex);

		$result = $this->Form->hour('Model.field', ['format' => 12, 'value' => '']);
		$expected = [
			['select' => ['name' => 'Model[field][hour]']],
			['option' => ['selected' => 'selected', 'value' => '']],
			'/option',
			['option' => ['value' => '01']],
			'1',
			'/option',
			['option' => ['value' => '02']],
			'2',
			'/option',
			$hoursRegex,
			'/select',
		];
		$this->assertHtml($expected, $result);

		$this->View->setRequest(
			$this->View->getRequest()->withData('Model.field', '2006-10-10 00:12:32')
		);
		$this->Form->create();
		$result = $this->Form->hour('Model.field', ['format' => 12]);
		$expected = [
			['select' => ['name' => 'Model[field][hour]']],
			['option' => ['value' => '']],
			'/option',
			['option' => ['value' => '01']],
			'1',
			'/option',
			['option' => ['value' => '02']],
			'2',
			'/option',
			$hoursRegex,
			['option' => ['value' => '12', 'selected' => 'selected']],
			'12',
			'/option',
			'/select',
		];
		$this->assertHtml($expected, $result);

		$this->View->setRequest($this->View->getRequest()->withData('Model.field', ''));
		$this->Form->create();
		$result = $this->Form->hour('Model.field', ['format' => 24, 'value' => '23']);
		$this->assertStringContainsString('<option value="23" selected="selected">23</option>', $result);

		$result = $this->Form->hour('Model.field', ['format' => 12, 'value' => '23']);
		$this->assertStringContainsString('<option value="11" selected="selected">11</option>', $result);

		$this->View->setRequest(
			$this->View->getRequest()->withData('Model.field', '2006-10-10 00:12:32')
		);
		$this->Form->create();
		$result = $this->Form->hour('Model.field', ['format' => 24]);
		$expected = [
			['select' => ['name' => 'Model[field][hour]']],
			['option' => ['value' => '']],
			'/option',
			['option' => ['value' => '00', 'selected' => 'selected']],
			'0',
			'/option',
			['option' => ['value' => '01']],
			'1',
			'/option',
			['option' => ['value' => '02']],
			'2',
			'/option',
			$hoursRegex,
			'/select',
		];
		$this->assertHtml($expected, $result);

		$this->View->setRequest($this->View->getRequest()->withData('Model.field', null));
		$this->Form->create();
		$result = $this->Form->hour('Model.field', ['format' => 24, 'value' => 'now']);
		$thisHour = date('H');
		$optValue = date('G');
		$this->assertRegExp('/<option value="' . $thisHour . '" selected="selected">' . $optValue . '<\/option>/', $result);

		$this->View->setRequest(
			$this->View->getRequest()->withData('Model.field', '2050-10-10 01:12:32')
		);
		$this->Form->create();
		$result = $this->Form->hour('Model.field', ['format' => 24]);
		$expected = [
			['select' => ['name' => 'Model[field][hour]']],
			['option' => ['value' => '']],
			'/option',
			['option' => ['value' => '00']],
			'0',
			'/option',
			['option' => ['value' => '01', 'selected' => 'selected']],
			'1',
			'/option',
			['option' => ['value' => '02']],
			'2',
			'/option',
			$hoursRegex,
			'/select',
		];
		$this->assertHtml($expected, $result);
	}

	/**
	 * testYear method
	 *
	 * Test generation of a year input.
	 *
	 * @return void
	 */
	public function testYear() {
		$this->View->setRequest(
			$this->View->getRequest()->withData('Contact.published', '2006-10-10')
		);
		$result = $this->Form->year('Model.field', ['value' => '', 'minYear' => 2006, 'maxYear' => 2007]);
		$expected = [
			['select' => ['name' => 'Model[field][year]']],
			['option' => ['selected' => 'selected', 'value' => '']],
			'/option',
			['option' => ['value' => '2007']],
			'2007',
			'/option',
			['option' => ['value' => '2006']],
			'2006',
			'/option',
			'/select',
		];
		$this->assertHtml($expected, $result);

		$result = $this->Form->year('Model.field', [
			'value' => '',
			'minYear' => 2006,
			'maxYear' => 2007,
			'orderYear' => 'asc',
		]);
		$expected = [
			['select' => ['name' => 'Model[field][year]']],
			['option' => ['selected' => 'selected', 'value' => '']],
			'/option',
			['option' => ['value' => '2006']],
			'2006',
			'/option',
			['option' => ['value' => '2007']],
			'2007',
			'/option',
			'/select',
		];
		$this->assertHtml($expected, $result);

		$result = $this->Form->year('Contact.published', [
			'empty' => false,
			'minYear' => 2006,
			'maxYear' => 2007,
		]);
		$expected = [
			['select' => ['name' => 'Contact[published][year]']],
			['option' => ['value' => '2007']],
			'2007',
			'/option',
			['option' => ['value' => '2006', 'selected' => 'selected']],
			'2006',
			'/option',
			'/select',
		];
		$this->assertHtml($expected, $result);

		$result = $this->Form->year('Contact.published', [
			'empty' => 'Published on',
		]);
		$this->assertStringContainsString('Published on', $result);
	}

	/**
	 * testControlDatetimePreEpoch method
	 *
	 * Test minYear being prior to the unix epoch.
	 *
	 * @return void
	 */
	public function testControlDatetimePreEpoch() {
		$start = date('Y') - 80;
		$end = date('Y') - 18;
		$result = $this->Form->control('birth_year', [
			'type' => 'date',
			'label' => 'Birth Year',
			'minYear' => $start,
			'maxYear' => $end,
			'month' => false,
			'day' => false,
			'empty' => false,
		]);
		$this->assertStringContainsString('value="' . $start . '">' . $start, $result);
		$this->assertStringContainsString('value="' . $end . '" selected="selected">' . $end, $result);
		$this->assertStringNotContainsString('value="00">00', $result);
	}

	/**
	 * test control() datetime & required attributes
	 *
	 * @return void
	 */
	public function testControlDatetimeRequired() {
		$result = $this->Form->control('birthday', [
			'type' => 'date',
			'required' => true,
		]);
		$this->assertStringContainsString(
			'<select name="birthday[year]" required="required"',
			$result
		);
	}

	/**
	 * testYearAutoExpandRange method
	 *
	 * @return void
	 */
	public function testYearAutoExpandRange() {
		$this->View->setRequest($this->View->getRequest()->withData('User.birthday', '1930-10-10'));
		$result = $this->Form->year('User.birthday');
		preg_match_all('/<option value="([\d]+)"/', $result, $matches);

		$result = $matches[1];
		$expected = range(date('Y') + 5, 1930);
		$this->assertEquals($expected, $result);

		$this->View->setRequest($this->View->getRequest()->withData('Project.release', '2050-10-10'));
		$this->Form->create();
		$result = $this->Form->year('Project.release');
		preg_match_all('/<option value="([\d]+)"/', $result, $matches);

		$result = $matches[1];
		$expected = range(2050, date('Y') - 5);
		$this->assertEquals($expected, $result);

		$this->View->setRequest($this->View->getRequest()->withData('Project.release', '1881-10-10'));
		$this->Form->create();
		$result = $this->Form->year('Project.release', [
			'minYear' => 1890,
			'maxYear' => 1900,
		]);
		preg_match_all('/<option value="([\d]+)"/', $result, $matches);

		$result = $matches[1];
		$expected = range(1900, 1881);
		$this->assertEquals($expected, $result);
	}

	/**
	 * testControlDate method
	 *
	 * Test that control() accepts the type of date and passes options in.
	 *
	 * @return void
	 */
	public function testControlDate() {
		$this->View->setRequest($this->View->getRequest()->withParsedBody([
			'month_year' => ['month' => date('m')],
		]));
		$this->Form->create($this->article);
		$result = $this->Form->control('month_year', [
				'label' => false,
				'type' => 'date',
				'minYear' => 2006,
				'maxYear' => 2008,
		]);
		$this->assertStringContainsString('value="' . date('m') . '" selected="selected"', $result);
		$this->assertStringNotContainsString('value="2008" selected="selected"', $result);
	}

	/**
	 * testControlLabelFalse method
	 *
	 * Test the label option being set to false.
	 *
	 * @return void
	 */
	public function testControlLabelFalse() {
		$this->Form->create($this->article);
		$result = $this->Form->control('title', ['label' => false]);
		/*
		$expected = [
			'div' => ['class' => 'input text required'],
			'input' => [
				'type' => 'text',
				'required' => 'required',
				'id' => 'title',
				'name' => 'title',
				'oninvalid' => 'this.setCustomValidity(&#039;This field is required&#039;); if (!this.validity.valid) this.setCustomValidity(&#039;This field is required&#039;)',
				'oninput' => 'this.setCustomValidity(&#039;&#039;)',
			],
			'/div',
		];
		$this->assertHtml($expected, $result);
		*/
		$expected = '<div class="input text required"><input type="text" name="title" required="required" data-validity-message="This field cannot be left empty" oninvalid="this.setCustomValidity(&#039;&#039;); if (!this.value) this.setCustomValidity(this.dataset.validityMessage)" oninput="this.setCustomValidity(&#039;&#039;)" id="title"/></div>';
		$this->assertSame($expected, $result);
	}

	/**
	 * testControlDateMaxYear method
	 *
	 * Let's say we want to only allow users born from 2006 to 2008 to register
	 * This being the first signup page, we still don't have any data.
	 *
	 * @return void
	 */
	public function testControlDateMaxYear() {
		$this->Form->create($this->article);
		$result = $this->Form->control('birthday', [
			'label' => false,
			'type' => 'date',
			'minYear' => 2006,
			'maxYear' => 2008,
			'default' => true,
		]);
		$this->assertStringContainsString('value="2008" selected="selected"', $result);
		$this->assertStringContainsString('value="2006"', $result);
		$this->assertStringNotContainsString('value="2005"', $result);
		$this->assertStringNotContainsString('value="2009"', $result);
	}

	/**
	 * testDateTimeWithGetForms method
	 *
	 * Test that datetime() works with GET style forms.
	 *
	 * @return void
	 */
	public function testDateTimeWithGetForms() {
		extract($this->dateRegex);
		$this->Form->create($this->article, ['type' => 'get']);
		$result = $this->Form->datetime('created');

		$this->assertStringContainsString('name="created[year]"', $result, 'year name attribute is wrong.');
		$this->assertStringContainsString('name="created[month]"', $result, 'month name attribute is wrong.');
		$this->assertStringContainsString('name="created[day]"', $result, 'day name attribute is wrong.');
		$this->assertStringContainsString('name="created[hour]"', $result, 'hour name attribute is wrong.');
		$this->assertStringContainsString('name="created[minute]"', $result, 'min name attribute is wrong.');
	}

}
