<?php
declare(strict_types = 1);
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link https://cakephp.org CakePHP(tm) Project
 * @since 3.0.0
 * @license https://opensource.org/licenses/mit-license.php MIT License
 */

namespace Shim\Test\TestCase\View\Widget;

use Cake\TestSuite\TestCase;
use Cake\View\Form\ContextInterface;
use Cake\View\StringTemplate;
use Cake\View\Widget\SelectBoxWidget;
use DateTime;
use PHPUnit\Framework\MockObject\MockObject;
use Shim\View\Widget\DateTimeWidget;

/**
 * DateTime input test case
 */
class DateTimeWidgetTest extends TestCase {

	protected ContextInterface|MockObject $context;

	protected DateTimeWidget $DateTime;

	/**
	 * @var \Cake\View\StringTemplate
	 */
	protected StringTemplate $templates;

	/**
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();

		date_default_timezone_set('UTC');

		$templates = [
			'select' => '<select name="{{name}}"{{attrs}}>{{content}}</select>',
			'option' => '<option value="{{value}}"{{attrs}}>{{text}}</option>',
			'optgroup' => '<optgroup label="{{label}}"{{attrs}}>{{content}}</optgroup>',
			'dateWidget' => '{{year}}{{month}}{{day}}{{hour}}{{minute}}{{second}}{{meridian}}',
		];
		$this->templates = new StringTemplate($templates);
		$this->context = $this->getMockBuilder(ContextInterface::class)->getMock();
		$selectBox = new SelectBoxWidget($this->templates);
		$this->DateTime = new DateTimeWidget($this->templates, $selectBox);
	}

	/**
	 * Data provider for testing various types of invalid selected values.
	 *
	 * @return array
	 */
	public static function invalidSelectedValuesProvider(): array {
		return [
			'false' => [false],
			'true' => [true],
			'string' => ['Bag of poop'],
			'array' => [[
				'derp' => 'hurt',
			]],
		];
	}

	/**
	 * test rendering selected values.
	 *
	 * @dataProvider invalidSelectedValuesProvider
	 * @param mixed $selected
	 * @return void
	 */
	public function testRenderSelectedInvalid(mixed $selected): void {
		$result = $this->DateTime->render(['val' => $selected], $this->context);
		$now = new DateTime();
		$format = '<option value="%s" selected="selected">%s</option>';
		$this->assertStringContainsString(
			sprintf($format, $now->format('Y'), $now->format('Y')),
			$result,
		);
	}

	/**
	 * test rendering empty selected.
	 *
	 * @return void
	 */
	public function testRenderSelectedEmpty(): void {
		$result = $this->DateTime->render([
			'val' => '',
			'year' => ['empty' => true],
			'month' => ['empty' => true],
			'day' => ['empty' => true],
			'hour' => ['empty' => true],
			'minute' => ['empty' => true],
		], $this->context);
		$this->assertStringContainsString('<option value="" selected="selected"></option>', $result);
		$this->assertDoesNotMatchRegularExpression('/value="\d+" selected="selected"/', $result);

		$result = $this->DateTime->render([
			'val' => ['year' => '', 'month' => '', 'day' => '', 'hour' => '', 'minute' => ''],
			'year' => ['empty' => true],
			'month' => ['empty' => true],
			'day' => ['empty' => true],
			'hour' => ['empty' => true],
			'minute' => ['empty' => true],
		], $this->context);
		$this->assertStringContainsString('<option value="" selected="selected"></option>', $result);
		$this->assertDoesNotMatchRegularExpression('/value="\d+" selected="selected"/', $result);
	}

	/**
	 * Test empty with custom values.
	 *
	 * @return void
	 */
	public function testRenderEmptyCustom(): void {
		$result = $this->DateTime->render([
			'val' => '',
			'year' => [
				'empty' => ['nope' => '(choose one)'],
			],
		], $this->context);
		$this->assertStringContainsString('<option value="nope">(choose one)</option>', $result);
		$this->assertStringNotContainsString('<optgroup', $result, 'No optgroups should be present.');
	}

	/**
	 * Data provider for testing various acceptable selected values.
	 *
	 * @return array
	 */
	public static function selectedValuesProvider(): array {
		$date = new DateTime('2014-01-20 12:30:45');

		return [
			'DateTime' => [$date],
			'string' => [$date->format('Y-m-d H:i:s')],
			'int string' => [$date->format('U')],
			'int' => [$date->getTimestamp()],
			'array' => [[
				'year' => '2014', 'month' => '01', 'day' => '20',
				'hour' => '12', 'minute' => '30', 'second' => '45',
			]],
		];
	}

	/**
	 * test rendering selected values.
	 *
	 * @dataProvider selectedValuesProvider
	 * @param mixed $selected
	 * @return void
	 */
	public function testRenderSelected(mixed $selected): void {
		$result = $this->DateTime->render(['val' => $selected], $this->context);
		$this->assertStringContainsString('<option value="2014" selected="selected">2014</option>', $result);
		$this->assertStringContainsString('<option value="01" selected="selected">1</option>', $result);
		$this->assertStringContainsString('<option value="20" selected="selected">20</option>', $result);

		if ((bool)getenv('TRAVIS')) {
			$this->assertStringContainsString('<option value="12" selected="selected">12</option>', $result);
		}

		$this->assertStringContainsString('<option value="30" selected="selected">30</option>', $result);
		$this->assertStringContainsString('<option value="45" selected="selected">45</option>', $result);
	}

	/**
	 * @return void
	 */
	public function testRenderInvalidDate(): void {
		$selected = [
			'year' => '2014', 'month' => '02', 'day' => '31',
			'hour' => '12', 'minute' => '30', 'second' => '45',
		];
		$result = $this->DateTime->render(['val' => $selected], $this->context);
		$this->assertStringContainsString('<option value="2014" selected="selected">2014</option>', $result);
		$this->assertStringContainsString('<option value="02" selected="selected">2</option>', $result);
		$this->assertStringContainsString('<option value="31" selected="selected">31</option>', $result);

		if ((bool)getenv('TRAVIS')) {
			$this->assertStringContainsString('<option value="12" selected="selected">12</option>', $result);
		}

		$this->assertStringContainsString('<option value="30" selected="selected">30</option>', $result);
		$this->assertStringContainsString('<option value="45" selected="selected">45</option>', $result);
	}

	/**
	 * Test that render() works with an array for val that is missing seconds.
	 *
	 * @return void
	 */
	public function testRenderSelectedNoSeconds(): void {
		$selected = [
			'year' => '2014', 'month' => '01', 'day' => '20',
			'hour' => '12', 'minute' => '30',
		];
		$result = $this->DateTime->render(['name' => 'created', 'val' => $selected], $this->context);
		$this->assertStringContainsString('name="created[year]"', $result);
		$this->assertStringContainsString('<option value="2014" selected="selected">2014</option>', $result);
		$this->assertStringContainsString('name="created[month]"', $result);
		$this->assertStringContainsString('<option value="01" selected="selected">1</option>', $result);
		$this->assertStringContainsString('name="created[day]"', $result);
		$this->assertStringContainsString('<option value="20" selected="selected">20</option>', $result);
		$this->assertStringContainsString('name="created[hour]"', $result);
		$this->assertStringContainsString('<option value="12" selected="selected">12</option>', $result);
		$this->assertStringContainsString('name="created[minute]"', $result);
		$this->assertStringContainsString('<option value="30" selected="selected">30</option>', $result);
		$this->assertStringContainsString('name="created[second]"', $result);
		$this->assertStringContainsString('<option value="30" selected="selected">30</option>', $result);
	}

	/**
	 * Test that render() adjusts hours based on meridian
	 *
	 * @return void
	 */
	public function testRenderSelectedMeridian(): void {
		$selected = [
			'year' => '2014', 'month' => '01', 'day' => '20',
			'hour' => '7', 'minute' => '30', 'meridian' => 'pm',
		];
		$result = $this->DateTime->render(['val' => $selected], $this->context);
		$this->assertStringContainsString('<option value="2014" selected="selected">2014</option>', $result);
		$this->assertStringContainsString('<option value="01" selected="selected">1</option>', $result);
		$this->assertStringContainsString('<option value="20" selected="selected">20</option>', $result);
		$this->assertStringContainsString('<option value="19" selected="selected">19</option>', $result);
	}

	/**
	 * Test rendering widgets with empty values.
	 *
	 * @return void
	 */
	public function testRenderEmptyValues(): void {
		$result = $this->DateTime->render([
			'year' => ['empty' => 'YEAR'],
			'month' => ['empty' => 'MONTH'],
			'day' => ['empty' => 'DAY'],
			'hour' => ['empty' => 'HOUR'],
			'minute' => ['empty' => 'MINUTE'],
			'second' => ['empty' => 'SECOND'],
			'meridian' => ['empty' => 'MERIDIAN'],
		], $this->context);
		$this->assertStringContainsString('<option value="" selected="selected">YEAR</option>', $result);
		$this->assertStringContainsString('<option value="" selected="selected">MONTH</option>', $result);
		$this->assertStringContainsString('<option value="" selected="selected">DAY</option>', $result);
		$this->assertStringContainsString('<option value="" selected="selected">HOUR</option>', $result);
		$this->assertStringContainsString('<option value="" selected="selected">MINUTE</option>', $result);
		$this->assertStringContainsString('<option value="" selected="selected">SECOND</option>', $result);
		$this->assertStringContainsString('<option value="" selected="selected">MERIDIAN</option>', $result);
	}

	/**
	 * Test rendering the default year widget.
	 *
	 * @return void
	 */
	public function testRenderYearWidgetDefaultRange(): void {
		$now = new DateTime();
		$result = $this->DateTime->render([
			'month' => false,
			'day' => false,
			'hour' => false,
			'minute' => false,
			'second' => false,
			'val' => $now,
		], $this->context);
		$year = $now->format('Y');
		$format = '<option value="%s" selected="selected">%s</option>';
		$this->assertStringContainsString(sprintf($format, $year, $year), $result);

		$format = '<option value="%s">%s</option>';
		$maxYear = $now->format('Y') + 5;
		$minYear = $now->format('Y') - 5;
		$this->assertStringContainsString(sprintf($format, $maxYear, $maxYear), $result);
		$this->assertStringContainsString(sprintf($format, $minYear, $minYear), $result);

		$nope = $now->format('Y') + 6;
		$this->assertStringNotContainsString(sprintf($format, $nope, $nope), $result);

		$nope = $now->format('Y') - 6;
		$this->assertStringNotContainsString(sprintf($format, $nope, $nope), $result);
	}

	/**
	 * Test ordering of year options.
	 *
	 * @return void
	 */
	public function testRenderYearWidgetOrdering(): void {
		$now = new DateTime('2014-01-01 12:00:00');
		$result = $this->DateTime->render([
			'name' => 'date',
			'year' => [
				'start' => 2013,
				'end' => 2015,
				'data-foo' => 'test',
				'order' => 'asc',
			],
			'month' => false,
			'day' => false,
			'hour' => false,
			'minute' => false,
			'second' => false,
			'val' => $now,
			'orderYear' => 'asc',
		], $this->context);
		$expected = [
			'select' => ['name' => 'date[year]', 'data-foo' => 'test'],
			['option' => ['value' => '2013']], '2013', '/option',
			['option' => ['value' => '2014', 'selected' => 'selected']], '2014', '/option',
			['option' => ['value' => '2015']], '2015', '/option',
			'/select',
		];
		$this->assertHtml($expected, $result);

		$result = $this->DateTime->render([
			'name' => 'date',
			'year' => [
				'start' => 2013,
				'end' => 2015,
				'order' => 'desc',
			],
			'month' => false,
			'day' => false,
			'hour' => false,
			'minute' => false,
			'second' => false,
			'val' => $now,
		], $this->context);
		$expected = [
			'select' => ['name' => 'date[year]'],
			['option' => ['value' => '2015']], '2015', '/option',
			['option' => ['value' => '2014', 'selected' => 'selected']], '2014', '/option',
			['option' => ['value' => '2013']], '2013', '/option',
			'/select',
		];
		$this->assertHtml($expected, $result);
	}

	/**
	 * Test that a selected value outside of the chosen
	 * year boundary is also included as an option.
	 *
	 * @return void
	 */
	public function testRenderYearWidgetValueOutOfBounds(): void {
		$now = new DateTime('2010-01-01 12:00:00');
		$result = $this->DateTime->render([
			'name' => 'date',
			'year' => [
				'start' => 2013,
				'end' => 2015,
			],
			'month' => false,
			'day' => false,
			'hour' => false,
			'minute' => false,
			'second' => false,
			'val' => $now,
		], $this->context);
		$expected = [
			'select' => ['name' => 'date[year]'],
			['option' => ['value' => '2015']], '2015', '/option',
			['option' => ['value' => '2014']], '2014', '/option',
			['option' => ['value' => '2013']], '2013', '/option',
			['option' => ['value' => '2012']], '2012', '/option',
			['option' => ['value' => '2011']], '2011', '/option',
			['option' => ['value' => '2010', 'selected' => 'selected']], '2010', '/option',
			'/select',
		];
		$this->assertHtml($expected, $result);

		$now = new DateTime('2013-01-01 12:00:00');
		$result = $this->DateTime->render([
			'name' => 'date',
			'year' => [
				'start' => 2010,
				'end' => 2011,
			],
			'month' => false,
			'day' => false,
			'hour' => false,
			'minute' => false,
			'second' => false,
			'val' => $now,
		], $this->context);
		$expected = [
			'select' => ['name' => 'date[year]'],
			['option' => ['value' => '2013', 'selected' => 'selected']], '2013', '/option',
			['option' => ['value' => '2012']], '2012', '/option',
			['option' => ['value' => '2011']], '2011', '/option',
			['option' => ['value' => '2010']], '2010', '/option',
			'/select',
		];
		$this->assertHtml($expected, $result);
	}

	/**
	 * Test rendering the month widget
	 *
	 * @return void
	 */
	public function testRenderMonthWidget(): void {
		$now = new DateTime('2010-09-01 12:00:00');
		$result = $this->DateTime->render([
			'name' => 'date',
			'year' => false,
			'day' => false,
			'hour' => false,
			'minute' => false,
			'second' => false,
			'val' => $now,
		], $this->context);
		$expected = [
			'select' => ['name' => 'date[month]'],
			['option' => ['value' => '01']], '1', '/option',
			['option' => ['value' => '02']], '2', '/option',
			['option' => ['value' => '03']], '3', '/option',
			['option' => ['value' => '04']], '4', '/option',
			['option' => ['value' => '05']], '5', '/option',
			['option' => ['value' => '06']], '6', '/option',
			['option' => ['value' => '07']], '7', '/option',
			['option' => ['value' => '08']], '8', '/option',
			['option' => ['value' => '09', 'selected' => 'selected']], '9', '/option',
			['option' => ['value' => '10']], '10', '/option',
			['option' => ['value' => '11']], '11', '/option',
			['option' => ['value' => '12']], '12', '/option',
			'/select',
		];
		$this->assertHtml($expected, $result);
	}

	/**
	 * Test rendering month widget with names and values without leading zeros.
	 *
	 * @return void
	 */
	public function testRenderMonthWidgetWithNamesNoLeadingZeros(): void {
		$now = new DateTime('2010-12-01 12:00:00');
		$result = $this->DateTime->render([
			'name' => 'date',
			'year' => false,
			'day' => false,
			'hour' => false,
			'minute' => false,
			'second' => false,
			'month' => ['data-foo' => 'test', 'names' => true, 'leadingZeroKey' => false],
			'meridian' => false,
			'val' => $now,
		], $this->context);
		$expected = [
			'select' => ['name' => 'date[month]', 'data-foo' => 'test'],
			['option' => ['value' => '1']], 'January', '/option',
			['option' => ['value' => '2']], 'February', '/option',
			['option' => ['value' => '3']], 'March', '/option',
			['option' => ['value' => '4']], 'April', '/option',
			['option' => ['value' => '5']], 'May', '/option',
			['option' => ['value' => '6']], 'June', '/option',
			['option' => ['value' => '7']], 'July', '/option',
			['option' => ['value' => '8']], 'August', '/option',
			['option' => ['value' => '9']], 'September', '/option',
			['option' => ['value' => '10']], 'October', '/option',
			['option' => ['value' => '11']], 'November', '/option',
			['option' => ['value' => '12', 'selected' => 'selected']], 'December', '/option',
			'/select',
		];
		$this->assertHtml($expected, $result);
		$this->assertStringNotContainsString(
			'<option value="01">January</option>',
			$result,
			'no 01 in value',
		);
		$this->assertStringNotContainsString(
			'value="0"',
			$result,
			'no 0 in value',
		);
		$this->assertStringNotContainsString(
			'value="00"',
			$result,
			'no 00 in value',
		);
		$this->assertStringNotContainsString(
			'value="13"',
			$result,
			'no 13 in value',
		);
	}

	/**
	 * Test rendering month widget with names.
	 *
	 * @return void
	 */
	public function testRenderMonthWidgetWithNames(): void {
		$now = new DateTime('2010-09-01 12:00:00');
		$result = $this->DateTime->render([
			'name' => 'date',
			'year' => false,
			'day' => false,
			'hour' => false,
			'minute' => false,
			'second' => false,
			'month' => ['data-foo' => 'test', 'names' => true],
			'val' => $now,
		], $this->context);
		$expected = [
			'select' => ['name' => 'date[month]', 'data-foo' => 'test'],
			['option' => ['value' => '01']], 'January', '/option',
			['option' => ['value' => '02']], 'February', '/option',
			['option' => ['value' => '03']], 'March', '/option',
			['option' => ['value' => '04']], 'April', '/option',
			['option' => ['value' => '05']], 'May', '/option',
			['option' => ['value' => '06']], 'June', '/option',
			['option' => ['value' => '07']], 'July', '/option',
			['option' => ['value' => '08']], 'August', '/option',
			['option' => ['value' => '09', 'selected' => 'selected']], 'September', '/option',
			['option' => ['value' => '10']], 'October', '/option',
			['option' => ['value' => '11']], 'November', '/option',
			['option' => ['value' => '12']], 'December', '/option',
			'/select',
		];
		$this->assertHtml($expected, $result);
	}

	/**
	 * Test rendering month widget with custom names.
	 *
	 * @return void
	 */
	public function testRenderMonthWidgetWithCustomNames(): void {
		$now = new DateTime('2010-09-01 12:00:00');
		$result = $this->DateTime->render([
			'name' => 'date',
			'year' => false,
			'day' => false,
			'hour' => false,
			'minute' => false,
			'second' => false,
			'month' => [
				'names' => ['01' => 'Jan', '02' => 'Feb'],
			],
			'val' => $now,
		], $this->context);
		$expected = [
			'select' => ['name' => 'date[month]'],
			['option' => ['value' => '01']], 'Jan', '/option',
			['option' => ['value' => '02']], 'Feb', '/option',
			'/select',
		];
		$this->assertHtml($expected, $result);
	}

	/**
	 * Test rendering the day widget.
	 *
	 * @return void
	 */
	public function testRenderDayWidget(): void {
		$now = new DateTime('2010-09-09 12:00:00');
		$result = $this->DateTime->render([
			'name' => 'date',
			'year' => false,
			'month' => false,
			'day' => [
				'data-foo' => 'test',
			],
			'hour' => false,
			'minute' => false,
			'second' => false,
			'val' => $now,
		], $this->context);
		$expected = [
			'select' => ['name' => 'date[day]', 'data-foo' => 'test'],
			['option' => ['value' => '01']], '1', '/option',
			['option' => ['value' => '02']], '2', '/option',
			['option' => ['value' => '03']], '3', '/option',
			['option' => ['value' => '04']], '4', '/option',
			['option' => ['value' => '05']], '5', '/option',
			['option' => ['value' => '06']], '6', '/option',
			['option' => ['value' => '07']], '7', '/option',
			['option' => ['value' => '08']], '8', '/option',
			['option' => ['value' => '09', 'selected' => 'selected']], '9', '/option',
			['option' => ['value' => '10']], '10', '/option',
			['option' => ['value' => '11']], '11', '/option',
			['option' => ['value' => '12']], '12', '/option',
			['option' => ['value' => '13']], '13', '/option',
			['option' => ['value' => '14']], '14', '/option',
			['option' => ['value' => '15']], '15', '/option',
			['option' => ['value' => '16']], '16', '/option',
			['option' => ['value' => '17']], '17', '/option',
			['option' => ['value' => '18']], '18', '/option',
			['option' => ['value' => '19']], '19', '/option',
			['option' => ['value' => '20']], '20', '/option',
			['option' => ['value' => '21']], '21', '/option',
			['option' => ['value' => '22']], '22', '/option',
			['option' => ['value' => '23']], '23', '/option',
			['option' => ['value' => '24']], '24', '/option',
			['option' => ['value' => '25']], '25', '/option',
			['option' => ['value' => '26']], '26', '/option',
			['option' => ['value' => '27']], '27', '/option',
			['option' => ['value' => '28']], '28', '/option',
			['option' => ['value' => '29']], '29', '/option',
			['option' => ['value' => '30']], '30', '/option',
			['option' => ['value' => '31']], '31', '/option',
			'/select',
		];
		$this->assertHtml($expected, $result);
	}

	/**
	 * Test rendering the hour picker in 24 hour mode.
	 *
	 * @return void
	 */
	public function testRenderHourWidget24StartAndEnd(): void {
		$now = new DateTime('2010-09-09 13:00:00');
		$result = $this->DateTime->render([
			'name' => 'date',
			'year' => false,
			'month' => false,
			'day' => false,
			'hour' => [
				'start' => 8,
				'end' => 16,
			],
			'minute' => false,
			'second' => false,
			'val' => $now,
		], $this->context);
		$this->assertStringContainsString('<select name="date[hour]">', $result);
		$this->assertStringNotContainsString(
			'<option value="01">1</option>',
			$result,
			'no 1 am',
		);
		$this->assertStringNotContainsString(
			'<option value="07">7</option>',
			$result,
			'contain 7',
		);
		$this->assertStringContainsString(
			'<option value="13" selected="selected">13</option>',
			$result,
			'selected value present',
		);
		$this->assertStringNotContainsString(
			'<option value="17">17</option>',
			$result,
			'contains 17 hours',
		);
		$this->assertStringNotContainsString('meridian', $result, '24hrs has no meridian');
	}

	/**
	 * Test rendering the hour picker in 24 hour mode.
	 *
	 * @return void
	 */
	public function testRenderHourWidget24(): void {
		$now = new DateTime('2010-09-09 13:00:00');
		$result = $this->DateTime->render([
			'name' => 'date',
			'year' => false,
			'month' => false,
			'day' => false,
			'hour' => [
				'format' => 24,
				'data-foo' => 'test',
			],
			'minute' => false,
			'second' => false,
			'val' => $now,
			'meridian' => [],
		], $this->context);
		$this->assertStringContainsString('<select name="date[hour]" data-foo="test">', $result);
		$this->assertStringContainsString('<option value="00">0</option>', $result);
		$this->assertStringContainsString(
			'<option value="01">1</option>',
			$result,
			'contain 1 am',
		);
		$this->assertStringContainsString(
			'<option value="05">5</option>',
			$result,
			'contain 5 am',
		);
		$this->assertStringContainsString(
			'<option value="13" selected="selected">13</option>',
			$result,
			'selected value present',
		);
		$this->assertStringContainsString('<option value="23">23</option>', $result);
		$this->assertStringNotContainsString('date[day]', $result, 'No day select.');
		$this->assertStringNotContainsString('value="0"', $result, 'No zero hour');
		$this->assertStringNotContainsString('value="24"', $result, 'No 25th hour');
		$this->assertStringNotContainsString('<select name="date[meridian]">', $result);
		$this->assertStringNotContainsString('<option value="pm" selected="selected">pm</option>', $result);
	}

	/**
	 * test selecting various options in 24 hr mode.
	 *
	 * @return void
	 */
	public function testRenderHour24SelectedValues(): void {
		$now = new DateTime('2010-09-09 23:00:00');
		$data = [
			'name' => 'date',
			'year' => false,
			'month' => false,
			'day' => false,
			'hour' => [],
			'minute' => false,
			'second' => false,
			'val' => $now,
		];
		$result = $this->DateTime->render($data, $this->context);
		$this->assertStringContainsString('<option value="23" selected="selected">23</option>', $result);

		$data['val'] = '2010-09-09 23:00:00';
		$result = $this->DateTime->render($data, $this->context);
		$this->assertStringContainsString('<option value="23" selected="selected">23</option>', $result);
	}

	/**
	 * Test rendering the hour widget in 12 hour mode.
	 *
	 * @return void
	 */
	public function testRenderHourWidget12(): void {
		$now = new DateTime('2010-09-09 13:00:00');
		$result = $this->DateTime->render([
			'name' => 'date',
			'year' => false,
			'month' => false,
			'day' => false,
			'hour' => [
				'format' => 12,
				'data-foo' => 'test',
			],
			'minute' => false,
			'second' => false,
			'val' => $now,
		], $this->context);
		$this->assertStringContainsString('<select name="date[hour]" data-foo="test">', $result);
		$this->assertStringContainsString(
			'<option value="01" selected="selected">1</option>',
			$result,
			'contain 1pm selected',
		);
		$this->assertStringContainsString(
			'<option value="05">5</option>',
			$result,
			'contain 5',
		);
		$this->assertStringContainsString(
			'<option value="12">12</option>',
			$result,
			'contain 12',
		);
		$this->assertStringNotContainsString(
			'<option value="13">13</option>',
			$result,
			'selected value present',
		);
		$this->assertStringNotContainsString('date[day]', $result, 'No day select.');
		$this->assertStringNotContainsString('value="0"', $result, 'No zero hour');

		$this->assertStringContainsString('<select name="date[meridian]">', $result);
		$this->assertStringContainsString('<option value="pm" selected="selected">pm</option>', $result);
	}

	/**
	 * Test rendering 12 hour widgets with empty options and no value.
	 *
	 * @return void
	 */
	public function testRenderHourWidget12Empty(): void {
		$result = $this->DateTime->render([
			'val' => ['hour' => '', 'minute' => '', 'meridian' => ''],
			'year' => false,
			'month' => false,
			'day' => false,
			'hour' => ['format' => 12, 'empty' => '--'],
			'minute' => ['empty' => '--'],
			'second' => false,
			'meridian' => ['empty' => '--'],
		], $this->context);
		$this->assertStringContainsString('<option value="" selected="selected">--</option>', $result);
		$this->assertDoesNotMatchRegularExpression('/value="\d+" selected="selected"/', $result);
	}

	/**
	 * Test rendering hour widget in 12 hour mode at midnight.
	 *
	 * @return void
	 */
	public function testRenderHourWidget12Midnight(): void {
		$now = new DateTime('2010-09-09 00:30:45');
		$result = $this->DateTime->render([
			'name' => 'date',
			'year' => false,
			'month' => false,
			'day' => false,
			'hour' => [
				'format' => 12,
			],
			'minute' => false,
			'second' => false,
			'val' => $now,
		], $this->context);
		$this->assertStringContainsString(
			'<option value="12" selected="selected">12</option>',
			$result,
			'12 is selected',
		);
	}

	/**
	 * Test rendering the hour picker in 12 hour mode.
	 *
	 * @return void
	 */
	public function testRenderHourWidget12StartAndEnd(): void {
		$now = new DateTime('2010-09-09 13:00:00');
		$result = $this->DateTime->render([
			'name' => 'date',
			'year' => false,
			'month' => false,
			'day' => false,
			'hour' => [
				'start' => 8,
				'end' => 12,
			],
			'minute' => false,
			'second' => false,
			'val' => $now,
		], $this->context);
		$this->assertStringContainsString('<select name="date[hour]">', $result);
		$this->assertStringContainsString(
			'<option value="08">8</option>',
			$result,
			'contains 8am',
		);
		$this->assertStringContainsString(
			'<option value="12">12</option>',
			$result,
			'contains 8am',
		);
		$this->assertStringNotContainsString(
			'<option value="01">1</option>',
			$result,
			'no 1 am',
		);
		$this->assertStringNotContainsString(
			'<option value="07">7</option>',
			$result,
			'contain 7',
		);
		$this->assertStringNotContainsString(
			'<option value="13" selected="selected">13</option>',
			$result,
			'selected value present',
		);
	}

	/**
	 * Test rendering the minute widget with no options.
	 *
	 * @return void
	 */
	public function testRenderMinuteWidget(): void {
		$now = new DateTime('2010-09-09 13:25:00');
		$result = $this->DateTime->render([
			'name' => 'date',
			'year' => false,
			'month' => false,
			'day' => false,
			'hour' => false,
			'minute' => [
				'data-foo' => 'test',
			],
			'second' => false,
			'val' => $now,
		], $this->context);
		$this->assertStringContainsString('<select name="date[minute]" data-foo="test">', $result);
		$this->assertStringContainsString(
			'<option value="00">00</option>',
			$result,
			'contains 00',
		);
		$this->assertStringContainsString(
			'<option value="05">05</option>',
			$result,
			'contains 05',
		);
		$this->assertStringContainsString(
			'<option value="25" selected="selected">25</option>',
			$result,
			'selected value present',
		);
		$this->assertStringContainsString(
			'<option value="59">59</option>',
			$result,
			'contains 59',
		);
		$this->assertStringNotContainsString('value="60"', $result, 'No 60 value');
	}

	/**
	 * Test rendering the minute widget with empty at zero options.
	 *
	 * @return void
	 */
	public function testRenderMinuteWidgetEmptyZeroDefault(): void {
		$now = new DateTime('2010-09-09 13:00:23');
		$result = $this->DateTime->render([
			'name' => 'date',
			'year' => false,
			'month' => false,
			'day' => false,
			'hour' => false,
			'minute' => [
				'data-foo' => 'test',
			],
			'empty' => '-',
			'default' => '',
			'second' => false,
			'val' => $now,
		], $this->context);
		$this->assertStringContainsString('<select name="date[minute]" data-foo="test">', $result);
		$this->assertStringContainsString(
			'<option value="">-</option>',
			$result,
			'contains empty option -',
		);
		$this->assertStringContainsString(
			'<option value="00" selected="selected">00</option>',
			$result,
			'selected value present and correct at 00',
		);
		$this->assertStringContainsString(
			'<option value="05">05</option>',
			$result,
			'contains 05',
		);
		$this->assertStringContainsString(
			'<option value="25">25</option>',
			$result,
			'contains 25',
		);
		$this->assertStringContainsString(
			'<option value="59">59</option>',
			$result,
			'contains 59',
		);
		$this->assertStringNotContainsString(
			'<option value="" selected="selected">-</option>',
			$result,
			'No 0 value as empty value',
		);
		$this->assertStringNotContainsString('value="0"', $result, 'No unpadded 0 value');
		$this->assertStringNotContainsString('value="60"', $result, 'No 60 value');
	}

	/**
	 * Test minutes with interval values.
	 *
	 * @return void
	 */
	public function testRenderMinuteWidgetInterval(): void {
		$now = new DateTime('2010-09-09 13:23:00');
		$result = $this->DateTime->render([
			'name' => 'date',
			'year' => false,
			'month' => false,
			'day' => false,
			'hour' => false,
			'minute' => [
				'interval' => 5,
			],
			'second' => false,
			'val' => $now,
		], $this->context);
		$this->assertStringContainsString('<select name="date[minute]">', $result);
		$this->assertStringContainsString(
			'<option value="00">00</option>',
			$result,
			'contains 00',
		);
		$this->assertStringContainsString(
			'<option value="05">05</option>',
			$result,
			'contains 05',
		);
		$this->assertStringContainsString(
			'<option value="25" selected="selected">25</option>',
			$result,
			'selected value present',
		);
		$this->assertStringContainsString(
			'<option value="55">55</option>',
			$result,
			'contains 55',
		);
		$this->assertStringNotContainsString('value="2"', $result, 'No 2 value');
		$this->assertStringNotContainsString('value="23"', $result, 'No 23 value');
		$this->assertStringNotContainsString('value="58"', $result, 'No 58 value');
		$this->assertStringNotContainsString('value="59"', $result, 'No 59 value');
		$this->assertStringNotContainsString('value="60"', $result, 'No 60 value');
	}

	/**
	 * Test rounding up and down.
	 *
	 * @return void
	 */
	public function testRenderMinuteWidgetIntervalRounding(): void {
		$now = new DateTime('2010-09-09 13:22:00');
		$data = [
			'name' => 'date',
			'year' => false,
			'month' => false,
			'day' => false,
			'hour' => false,
			'minute' => [
				'interval' => 5,
				'round' => 'up',
			],
			'second' => false,
			'val' => $now,
		];
		$result = $this->DateTime->render($data, $this->context);
		$this->assertStringContainsString(
			'<option value="25" selected="selected">25</option>',
			$result,
			'selected value present',
		);
		$this->assertStringNotContainsString('value="23"', $result, 'No 23 value');

		$data['minute']['round'] = 'down';
		$result = $this->DateTime->render($data, $this->context);
		$this->assertStringContainsString(
			'<option value="20" selected="selected">20</option>',
			$result,
			'selected value present',
		);
		$this->assertStringNotContainsString('value="23"', $result, 'No 23 value');
	}

	/**
	 * Test that minute interval rounding can effect hours and days.
	 *
	 * @return void
	 */
	public function testMinuteIntervalHourRollover(): void {
		$now = new DateTime('2010-09-09 23:58:00');
		$result = $this->DateTime->render([
			'name' => 'date',
			'year' => false,
			'month' => false,
			'minute' => [
				'interval' => 5,
				'round' => 'up',
			],
			'second' => false,
			'val' => $now,
		], $this->context);

		$this->assertStringContainsString(
			'<option value="00" selected="selected">00</option>',
			$result,
			'selected minute present',
		);
		$this->assertStringContainsString(
			'<option value="10" selected="selected">10</option>',
			$result,
			'selected day present',
		);
	}

	/**
	 * Test render seconds basic.
	 *
	 * @return void
	 */
	public function testRenderSecondsWidget(): void {
		$now = new DateTime('2010-09-09 13:00:25');
		$result = $this->DateTime->render([
			'name' => 'date',
			'year' => false,
			'month' => false,
			'day' => false,
			'hour' => false,
			'minute' => false,
			'second' => [
				'data-foo' => 'test',
			],
			'val' => $now,
		], $this->context);
		$this->assertStringContainsString('<select name="date[second]" data-foo="test">', $result);
		$this->assertStringContainsString(
			'<option value="00">00</option>',
			$result,
			'contains 00',
		);
		$this->assertStringContainsString(
			'<option value="01">01</option>',
			$result,
			'contains 01',
		);
		$this->assertStringContainsString(
			'<option value="05">05</option>',
			$result,
			'contains 05',
		);
		$this->assertStringContainsString(
			'<option value="25" selected="selected">25</option>',
			$result,
			'selected value present',
		);
		$this->assertStringContainsString(
			'<option value="59">59</option>',
			$result,
			'contains 59',
		);
		$this->assertStringNotContainsString('value="0"', $result, 'No unpadded zero value');
		$this->assertStringNotContainsString('value="60"', $result, 'No 60 value');
	}

	/**
	 * Test the merdian select.
	 *
	 * @return void
	 */
	public function testRenderMeridianWidget(): void {
		$now = new DateTime('2010-09-09 13:00:25');
		$result = $this->DateTime->render([
			'name' => 'date',
			'year' => false,
			'month' => false,
			'day' => false,
			'hour' => false,
			'minute' => false,
			'second' => false,
			'meridian' => [],
			'val' => $now,
		], $this->context);
		$expected = [
			'select' => ['name' => 'date[meridian]'],
			['option' => ['value' => 'am']], 'am', '/option',
			['option' => ['value' => 'pm', 'selected' => 'selected']], 'pm', '/option',
			'/select',
		];
		$this->assertHtml($expected, $result);

		$now = new DateTime('2010-09-09 09:00:25');
		$result = $this->DateTime->render([
			'name' => 'date',
			'year' => false,
			'month' => false,
			'day' => false,
			'hour' => false,
			'minute' => false,
			'second' => false,
			'meridian' => [],
			'val' => $now,
		], $this->context);
		$expected = [
			'select' => ['name' => 'date[meridian]'],
			['option' => ['value' => 'am', 'selected' => 'selected']], 'am', '/option',
			['option' => ['value' => 'pm']], 'pm', '/option',
			'/select',
		];
		$this->assertHtml($expected, $result);
	}

	/**
	 * Test rendering with templateVars
	 *
	 * @return void
	 */
	public function testRenderTemplateVars(): void {
		$templates = [
			'select' => '<select data-s="{{svar}}" name="{{name}}"{{attrs}}>{{content}}</select>',
			'option' => '<option data-o="{{ovar}}" value="{{value}}"{{attrs}}>{{text}}</option>',
			'optgroup' => '<optgroup label="{{label}}"{{attrs}}>{{content}}</optgroup>',
			'dateWidget' => '{{year}}{{month}}{{day}}{{hour}}{{minute}}{{second}}{{meridian}}{{help}}',
		];
		$this->templates->add($templates);
		$result = $this->DateTime->render([
			'name' => 'date',
			'year' => [
				'templateVars' => ['ovar' => 'not-default'],
			],
			'month' => [
				'names' => true,
			],
			'hour' => false,
			'minute' => false,
			'second' => false,
			'meridian' => [],
			'templateVars' => [
				'svar' => 's-val',
				'ovar' => 'o-val',
				'help' => 'some help',
			],
		], $this->context);

		$this->assertStringContainsString('<option data-o="not-default" value="2020">2020</option>', $result);
		$this->assertStringContainsString('<option data-o="o-val" value="01">January</option>', $result);
		$this->assertStringContainsString('<select data-s="s-val" name="date[year]">', $result);
		$this->assertStringContainsString('<select data-s="s-val" name="date[month]">', $result);
		$this->assertStringContainsString('</select>some help', $result);
	}

	/**
	 * Test that secureFields omits removed selects
	 *
	 * @return void
	 */
	public function testSecureFields(): void {
		$data = [
			'name' => 'date',
		];
		$result = $this->DateTime->secureFields($data);
		$expected = [
			'date[year]', 'date[month]', 'date[day]',
			'date[hour]', 'date[minute]', 'date[second]',
		];
		$this->assertEquals($expected, $result, 'No meridian on 24hr input');

		$data = [
			'name' => 'date',
			'hour' => ['format' => 24],
		];
		$result = $this->DateTime->secureFields($data);
		$this->assertEquals($expected, $result, 'No meridian on 24hr input');

		$data = [
			'name' => 'date',
			'year' => false,
			'month' => false,
			'day' => false,
			'hour' => [
				'format' => 12,
				'data-foo' => 'test',
			],
			'minute' => false,
			'second' => false,
		];
		$result = $this->DateTime->secureFields($data);
		$expected = [
			'date[hour]', 'date[meridian]',
		];
		$this->assertEquals($expected, $result);
	}

}
