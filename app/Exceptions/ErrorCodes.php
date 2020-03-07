<?php

namespace App\Exceptions;

class ErrorCodes
{
    const DATA_NOT_FOUND = [
        'header' => 404,
        'code' => '8bcd3128-3277-470d-b777-d9ef504d19a5',
        'message' => 'Data not found'
    ];

    const DUPLICATED_DATA = [
        'header' => 409,
        'code' => 'ea473739-53ab-45b2-84eb-7c477148a25f ',
        'message' => 'Duplicated Data'
    ];

    const EMPTY_CREDENTIALS = [
        'header' => 401,
        'code' => '8bcd3147-46b6-4f00-9825-e66406752750',
        'message' => 'Unauthorized - Empty credentials'
    ];

    const EXTERNAL_API = [
        'header' => 503,
        'code' => '5cb67ff5-51db-402c-96e1-a8b8b31bed00',
        'message' => 'External Service Unavailable'
    ];

    const FORBIDDEN_ACCESS = [
        'header' => 403,
        'code' => '8bcd315d-6afa-4a04-8e09-de86b4e1f4e7',
        'message' => 'Forbiden access'
    ];

    const GENERIC_ERROR = [
        'header' => 500,
        'code' => '8bcd316f-a95c-4dc6-848a-775483ee7ba5',
        'message' => 'Generic error'
    ];

    const INPUT_VALIDATION_ERROR = [
        'header' => 422,
        'code' => '8bcd3187-43ca-4a01-8b29-93335afd3c29',
        'message' => 'Validation error'
    ];

    const INVALID_CREDENTIALS = [
        'header' => 401,
        'code' => '8bcd327a-1c5a-4a51-a860-fcc066c8b88d',
        'message' => 'Unauthorized - Invalid credentials'
    ];

    const INVALID_ORDER_ARRAY_STRUCTURE = [
        'header' => 500,
        'code' => '8bcd339e-0607-4096-a830-39c383eeb281',
        'message' => 'Invalid order array structure'
    ];

    const INVALID_ORDER_OPERATOR = [
        'header' => 500,
        'code' => '8bcd3297-9562-4e76-b87e-797675b55593',
        'message' => 'Invalid order operator'
    ];

    const INVALID_REFINE_ARRAY_STRUCTURE = [
        'header' => 500,
        'code' => '8bcd32ac-d9b6-42a9-a85e-604a1de95765',
        'message' => 'Invalid refine array structure'
    ];

    const INVALID_REFINE_OPERATOR = [
        'header' => 500,
        'code' => '8bcd32c2-e6e5-4c44-add2-8fcc3fcb1586',
        'message' => 'Invalid refine operator'
    ];

    const INVALID_REFINE_PARAMS = [
        'header' => 500,
        'code' => '8bcd32d4-b621-4054-9755-f554609ad590',
        'message' => 'Invalid refine params'
    ];

    const METHOD_NOT_ALLOWED = [
        'header' => 405,
        'code' => '8bcd32e9-8a87-4ec6-b44c-75719109355a',
        'message' => 'Method not allowed'
    ];

    const MISSING_ROUTE_ALIAS = [
        'header' => 400,
        'code' => '8bcd32fc-34e6-4008-adbf-69e9d373749b',
        'message' => 'Missing route alias'
    ];

    const MISSING_ROUTE_VALIDATE = [
        'header' => 400,
        'code' => '8bcd3332-1856-497a-bec6-c5f666f39a74',
        'message' => 'Missing route validate'
    ];

    const NOTHING_TO_SAVE = [
        'header' => 400,
        'code' => '8bcd3344-90e3-4a33-8f91-04178dbcf7eb',
        'message' => 'Nothing to save'
    ];

    const NOT_SAVE_DATA = [
        'header' => 500,
        'code' => '8bcd3353-edfe-47ce-86d3-ee2676994b47',
        'message' => 'Error on save data'
    ];

    const PAGINATE_NOT_FOUND = [
        'header' => 404,
        'code' => '8bcd3361-60bf-4a47-aa8b-82a5be5b76f8',
        'message' => 'Paginate not found'
    ];
}
