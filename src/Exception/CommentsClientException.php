<?php

declare(strict_types=1);

namespace Vendor\CommentsClient\Exception;

/**
 * Корневое исключение библиотеки.
 *
 * Любая ошибка библиотеки наследуется от этого класса, поэтому потребитель
 * может поймать всё одним catch-блоком по типу CommentsClientException.
 *
 * @internal класс не предназначен для наследования снаружи пакета
 */
abstract class CommentsClientException extends \RuntimeException
{
}
