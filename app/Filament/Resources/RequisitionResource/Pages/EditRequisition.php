<?php

namespace App\Filament\Resources\RequisitionResource\Pages;

use App\Filament\Resources\RequisitionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRequisition extends EditRecord
{
    protected static string $resource = RequisitionResource::class;

    /**
     * @param $propertyName
     */
	public function updated($propertyName) : void {
		$data = $this->validateOnly($propertyName);
		$data = $data['data'];
        $this->save();
		// dd('it worked, do what you need to do.',$propertyName, $data);
	}

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
