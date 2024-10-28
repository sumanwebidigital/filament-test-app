<?php

namespace App\Filament\Resources;

use App\Filament\Exports\PostExporter;
use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers\AuthorsRelationManager;
use App\Filament\Resources\PostResource\RelationManagers\CommentsRelationManager;
use App\Models\Post;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationGroup = 'Blog';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Create New Post')
                ->tabs([
                    Tab::make('Base')
                    ->icon('heroicon-o-inbox')
                    ->schema([
                        TextInput::make('title')->rules(['min:3', 'max:5'])->required(),
                        TextInput::make('slug')->required(),
                        Select::make('category_id')
                            ->label('Category')
                            //->options(Category::all()->pluck('name', 'id')),
                            //->multiple()
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        ColorPicker::make('color')->required(),
                    ]),
                    Tab::make('Content')
                    ->icon('heroicon-o-rectangle-stack')
                    ->schema([
                        MarkdownEditor::make('content')->required()->columnSpan('full'),
                    ]),
                    Tab::make('Meta')
                    ->icon('heroicon-o-circle-stack')
                    ->schema([
                        Section::make('Image')
                        ->collapsible()
                        ->schema([
                            FileUpload::make('thumbnail')
                                ->disk('public')
                                ->directory('thumbnails')->columnSpanFull(),
                        ])->columnSpan(1),
                    
                        Section::make('Meta')->schema([
                            TagsInput::make('tags')->required(),
                            Toggle::make('published'),
                        ]),
                    ]),
                ])->columnSpanFull()
                //->activeTab(1)
                ->persistTabInQueryString(),


                

                // Section::make('Create a Post')
                //     ->description('This is blog post.')
                //     ->collapsible()
                //     ->schema([
                //         // Group::make()->schema([
                //         //     TextInput::make('title')->rules(['min:3', 'max:5'])->required(),
                //         //     TextInput::make('slug')->required(),
                //         // ]),
                        
                //         // Group::make()->schema([
                //         //     Select::make('category_id')
                //         //         ->label('Category')
                //         //         //->options(Category::all()->pluck('name', 'id')),
                //         //         ->relationship('category', 'name')
                //         //         ->searchable()
                //         //         ->required(),
                //         //     ColorPicker::make('color')->required(),
                //         // ]),
        
                //         MarkdownEditor::make('content')->required()->columnSpan('full'),
                //     ])->columnSpan(2)->columns(2),
                
                // Group::make()->schema([
                //     Section::make('Image')
                //         ->collapsible()
                //         ->schema([
                //             FileUpload::make('thumbnail')
                //                 ->disk('public')
                //                 ->directory('thumbnails')->columnSpanFull(),
                //         ])->columnSpan(1),
                    
                //     Section::make('Meta')->schema([
                //         TagsInput::make('tags')->required(),
                //         Toggle::make('published'),
                //     ]),
                //     // Section::make('Authors')->schema([
                //     //     Select::make('authors')
                //     //         ->label('Co Authors')
                //     //         ->multiple()
                //     //         ->relationship('authors', 'name'),
                //     // ]),
                // ]),
            ])->columns(3);
            // ->columns([
            //     'default' => 1,
            //     'md' => 2,
            //     'lg' => 3,
            //     'xl' => 4
            // ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                ImageColumn::make('thumbnail')
                    ->toggleable(),
                ColorColumn::make('color')
                    ->toggleable(),
                TextColumn::make('title')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('slug')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                SelectColumn::make('category.name')
                    ->label('Category')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TagsColumn::make('tags')
                    ->toggleable(),
                ToggleColumn::make('published')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Published On')
                    //->date()
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
               
            ])
            ->filters([
            //    Filter::make('Published Post')
            //    ->query(function (Builder $query) : Builder{
            //         return $query->where('published', true);
            //    }), 
            //    Filter::make('Unpublished Post')
            //    ->query(function (Builder $query) : Builder{
            //         return $query->where('published', false);
            //    }), 
            
                TernaryFilter::make('published'),
                SelectFilter::make('category_id')
                ->label('Category')
                //->options(Category::all()->pluck('name', 'id'))
                ->multiple()
                ->relationship('category', 'name')
                ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                ExportAction::make()->exporter(PostExporter::class),
            ])
            ->bulkActions([
                ExportBulkAction::make()->exporter(PostExporter::class),
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            AuthorsRelationManager::class,
            CommentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
