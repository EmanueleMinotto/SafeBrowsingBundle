<?php

namespace EmanueleMinotto\SafeBrowsingBundle\Validator\Constraints;

use Doctrine\Common\Cache\ArrayCache;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\TransferException;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Validator\Tests\Constraints\AbstractConstraintValidatorTest;

/**
 * @author Emanuele Minotto <minottoemanuele@gmail.com>
 *
 * @covers EmanueleMinotto\SafeBrowsingBundle\Validator\Constraints\Safe
 * @covers EmanueleMinotto\SafeBrowsingBundle\Validator\Constraints\SafeValidator
 */
class SafeValidatorTest extends AbstractConstraintValidatorTest
{
    /**
     * Gets tested validator.
     *
     * @return SafeValidator
     */
    protected function createValidator($expectedOutput = 'malware')
    {
        $validator = new SafeValidator([
            'key' => 'apikey',
            'client' => 'app',
            'appver' => '2.3.4',
            'pver' => 3.1,
        ]);

        $httpClient = $this->getMock(ClientInterface::class);
        $response = $this->getMock(ResponseInterface::class);

        $response
            ->method('getBody')
            ->willReturn($expectedOutput);

        $httpClient
            ->method('request')
            ->willReturn($response);

        $validator->setHttpClient($httpClient);

        return $validator;
    }

    /**
     * Test null is valid.
     */
    public function testNullIsValid()
    {
        $this->validator->validate(null, new Safe());
        $this->assertNoViolation();
    }

    /**
     * Test empty string is valid.
     */
    public function testEmptyStringIsValid()
    {
        $this->validator->validate('', new Safe());
        $this->assertNoViolation();
    }

    /**
     * Test invalid values.
     *
     * @dataProvider invalidValuesProvider
     */
    public function testInvalidValues($value, $expected)
    {
        $this->validator = $this->createValidator($expected);
        $this->validator->initialize($this->context);

        $this->validator->validate($value, new Safe());

        $this
            ->buildViolation('This URL is not safe (%response%).')
            ->setParameter('%response%', $expected)
            ->assertRaised();
    }

    /**
     * @return array
     */
    public function invalidValuesProvider()
    {
        return [
            ['localhost', 'malware'],
            ['example.com', 'phishing,malware'],
        ];
    }

    /**
     * Test valid values.
     *
     * @dataProvider validValuesProvider
     */
    public function testValidValues($value, $expected)
    {
        $this->validator = $this->createValidator($expected);
        $this->validator->initialize($this->context);

        $this->validator->validate($value, new Safe());

        $this->assertNoViolation();
    }

    /**
     * @return array
     */
    public function validValuesProvider()
    {
        return [
            ['localhost', 'ok'],
            ['example.com', false],
        ];
    }

    /**
     * Test cached output.
     */
    public function testCachedValue()
    {
        $this->validator = $this->createValidator('ok');
        $this->validator->setCache(new ArrayCache([
            'localhost' => 'ok',
        ]));

        $this->validator->initialize($this->context);

        $this->validator->validate('localhost', new Safe());
        $this->assertNoViolation();
    }

    /**
     * Test Guzzle exception.
     */
    public function testGuzzleException()
    {
        $httpClient = $this->getMock(ClientInterface::class);
        $httpClient
            ->method('request')
            ->will($this->throwException(new TransferException()));

        $this->validator = $this->createValidator('ok');
        $this->validator->setHttpClient($httpClient);
        $this->validator->initialize($this->context);

        $this->validator->validate('localhost', new Safe());
        $this->assertNoViolation();
    }
}
