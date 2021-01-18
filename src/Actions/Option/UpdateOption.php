<?php

namespace Bigmom\Poll\Actions\Option;

use Bigmom\Poll\Models\Option;
use Bigmom\Poll\Objects\Status;
use Illuminate\Support\Facades\Validator;

class UpdateOption
{
    public function run(array $input): Status
    {
        $this->validate($input);

        $option = Option::find($input['option_id']);
        $option->text = $input['text'];
        $option->save();

        return new Status(0, 'Option successfully updated.');
    }

    public function validate(array $input)
    {
        Validator::make($input, [
            'option_id' => ['required', 'exists:options,id'],
            'text' => ['required', 'string', 'max:191'],
        ])->validate();
    }
}
