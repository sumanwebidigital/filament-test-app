<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use App\Models\User;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationGroup = 'Roles & Permission';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                    TextInput::make('name')
                        ->minLength(2)
                        ->maxLength(255)
                        ->required()
                        ->unique(ignoreRecord: true),

                    // Select::make('permissions')
                        // ->multiple()
                        // ->relationship('permissions', 'name')
                        // ->searchable()
                        // ->preload()
                        // ->required(),
                


                    Section::make('Permissions')
                        ->schema(function ($record) {
                            // Fetch distinct permission from the database
                            $distinctPermissions = DB::table('permissions')
                                ->selectRaw('DISTINCT SUBSTRING_INDEX(name, " ", -1) as distinct_permissions')
                                ->pluck('distinct_permissions')
                                ->toArray();
                            $permissionsPattern = implode('|', array_map('preg_quote', $distinctPermissions));
                            $regex = "/(.*?)($permissionsPattern)$/";
                            $permissions = DB::table('permissions')->get(['name', 'id']);
                            

                            // Group permissions by category
                            $groupedPermissions = [];
                            foreach ($permissions as $permission) {
                                // Assuming your permission names follow a pattern like "Create Post", "Delete Post", etc.
                                if (preg_match($regex, $permission->name, $matches)) {
                                    $group = $matches[2]; // This will get 'Post' or 'Category'
                                    $groupedPermissions[$group][$permission->id] = $permission->name; // Grouped by the category
                                }
                            }

                            // Create checkbox lists for each group dynamically
                            $schema = [];
                            foreach ($groupedPermissions as $group => $permissions) {
                                $schema[] = Group::make()->schema([
                                    // Dynamic label for each group
                                    CheckboxList::make(strtolower($group) . '_permissions') // Unique field name
                                    ->options($permissions) // Options for this group
                                    ->afterStateHydrated(function ($component, $state) use ($record, $group) {
                                        // Check if we have a record (edit mode)
                                        if ($record) {
                                            // Fetch the existing permissions IDs for this group
                                            $selectedPermissions = $record->permissions()
                                                ->where('name', 'like', "%$group")
                                                ->pluck('id')
                                                ->toArray();
                                            // Set the state for this component with selected permissions
                                            $component->state($selectedPermissions);
                                        }
                                    })
                                    ->columns(5) // Display in two columns
                                    ->label("{$group} Permissions"),
                                    
                                ]);
                            }


                            return $schema;
                        })
                        ->afterStateUpdated(function ($state, $get, $record) {
                            // Collect all selected permission IDs from each category
                            $distinctPermissions = DB::table('permissions')
                                ->selectRaw('DISTINCT LOWER(SUBSTRING_INDEX(name, " ", -1)) as distinct_permissions')
                                ->pluck('distinct_permissions')
                                ->toArray();
                            
                            $allSelectedPermissions = [];
                            foreach($distinctPermissions as $permission){
                                // Merge selected permissions for each distinct permission
                                $allSelectedPermissions = array_merge(
                                    $allSelectedPermissions, // Retain previously merged permissions
                                    $get($permission . '_permissions') ?? [] // Merge current permissions
                                );
                            }
                    
                            // Sync selected permissions with the role
                            $record->permissions()->sync($allSelectedPermissions);
                        }),
                ]),           
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('name'),
                TextColumn::make('created_at'),
                TextColumn::make('updated_at'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->disabled(fn ($record) => $record->name == User::ROLE_ADMIN)
                    ->tooltip('Cannot delete ' . User::ROLE_ADMIN . " role"),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->disabled(fn ($records) => $records && $records->contains('name', 'Admin'))
                        ->tooltip('Cannot delete the Admin role'),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
