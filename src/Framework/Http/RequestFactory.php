<?php


namespace Framework\Http;


class RequestFactory
{
    public static function fromGlobal()
    {
        return (new Request())
            ->withParsedBody($_POST)
            ->withQueryParams($_GET);
    }
}