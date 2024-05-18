<?php

namespace App\Filament\Resources\RequisitionResource\Pages;

use App\Enums\RequisitionStatus;
use App\Filament\Resources\RequisitionResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListRequisitions extends ListRecords
{
    protected static string $resource = RequisitionResource::class;

    /**
     * @return array<string | int, Tab>
     */
    public function getTabs(): array
    {
        return [

            'all' => Tab::make('All'),
            'draft' => Tab::make('Drafts')
                ->modifyQueryUsing(function($query) {
                    return $query->whereStatus(RequisitionStatus::DRAFT);
                }),
            'submitted' => Tab::make('Submitted')
                ->modifyQueryUsing(function($query) {
                    return $query->whereStatus(RequisitionStatus::SUBMITTED);
                }),
            'approved' => Tab::make('Approved')
                ->modifyQueryUsing(function($query) {
                    return $query->whereStatus(RequisitionStatus::APPROVED);
                }),
            'ordered' => Tab::make('Ordered')
                ->modifyQueryUsing(function($query) {
                    return $query->whereStatus(RequisitionStatus::ORDERED);
                }),
            'rejected' => Tab::make('Rejected')
                ->modifyQueryUsing(function($query) {
                    return $query->whereStatus(RequisitionStatus::REJECTED);
                }),

        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
