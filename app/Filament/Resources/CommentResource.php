<?php

namespace App\Filament\Resources;

use App\Filament\Exports\CommentExporter;
use App\Filament\Resources\CommentResource\Pages;
use App\Filament\Resources\CommentResource\RelationManagers;
use App\Filament\Resources\PostResource\RelationManagers\CommentsRelationManager;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CommentResource extends Resource
{
    protected static ?string $model = Comment::class;

    // protected static ?string $modelLabel = 'Comments';

    protected static ?string $navigationGroup = 'Review & Rating';

    // protected static ?string $navigationParentItem = 'Users';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                ->relationship('user', 'name')
                ->searchable()
                ->preload(),

                TextInput::make('comment'),

                MorphToSelect::make('commentable')
                ->label('Comment Type')
                ->types([
                    MorphToSelect\Type::make(Post::class)->titleAttribute('title'),
                    MorphToSelect\Type::make(User::class)->titleAttribute('email'),
                    MorphToSelect\Type::make(Comment::class)->titleAttribute('id'),
                ])
                ->searchable()
                ->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('comment'),
                TextColumn::make('commentable_type'),
                TextColumn::make('commentable_id'),
                TextColumn::make('comment'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                ExportAction::make()->exporter(CommentExporter::class),
            ])
            ->bulkActions([
                ExportBulkAction::make()->exporter(CommentExporter::class),
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            CommentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListComments::route('/'),
            'create' => Pages\CreateComment::route('/create'),
            'edit' => Pages\EditComment::route('/{record}/edit'),
        ];
    }
}
