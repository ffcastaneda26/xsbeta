            Forms\Components\Group::make()->schema([
                    Forms\Components\Radio::make('type')
                        ->translateLabel()
                        ->options(VoucherTypeEnum::class)
                        ->required()
                        ->inline()
                        ->default(VoucherTypeEnum::Both)
                        ->columnSpanFull(),
                    Forms\Components\Select::make('document_type')
                        ->translateLabel()
                        ->options(VoucherDocumentTypeEnum::class)
                        ->required(),
                    Forms\Components\Select::make('periodo')
                        ->translateLabel()
                        ->required()
                        ->options(function () {
                            $months = [
                                1 => __('January'),
                                2 => __('February'),
                                3 => __('March'),
                                4 => __('April'),
                                5 => __('May'),
                                6 => __('June'),
                                7 => __('July'),
                                8 => __('August'),
                                9 => __('September'),
                                10 => __('October'),
                                11 => __('November'),
                                12 => __('December'),

                            ];
                            return $months;

                        }),
                    Forms\Components\DatePicker::make('date')
                        ->translateLabel()
                        ->default(now())
                        ->maxDate($maxDate)
                        ->minDate($minDate)
                        ->format('d-m-Y')
                        ->default(fn() => now()->greaterThan($maxDate) ? $maxDate : now())
                        ->required(),
                    Forms\Components\TextInput::make('folio')
                        ->translateLabel()
                        ->readOnly()
                        ->required(),

                ])->columns(2),
                Forms\Components\Group::make()->schema([

                    Forms\Components\Textarea::make('glosa')
                        ->translateLabel()
                        ->required()
                        ->rows(7)
                        ->columnSpanFull(),
                ]),
