<?php

namespace App\Http\Controllers;

use App\Models\Build;
use App\Models\Category;
use App\Models\Component;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BuildController extends Controller
{
    public function index(?Category $category = null): View
    {
        $query = Build::where('is_blocked', false)
            ->with(['user.role', 'category', 'components', 'comments.user.role'])
            ->latest()
            ->orderByDesc('id');

        if ($category !== null) {
            $query->where('category_id', $category->id);
        }

        return view('index', [
            'builds' => $query->paginate(5),
            'categories' => Category::orderBy('id')->get(),
            'activeCategory' => $category,
        ]);
    }

    public function create(Request $request): View
    {
        return view('builder', $this->builderViewData($request, null));
    }

    public function edit(Request $request, Build $build): View
    {
        abort_unless($request->user()->hasRole('Администратор') || $build->user_id === $request->user()->id, 403);

        $build->load('components');

        return view('builder', $this->builderViewData($request, $build));
    }

    public function store(Request $request): RedirectResponse
    {
        [$payload, $componentIds] = $this->validatedBuildData($request);
        $payload['user_id'] = $request->user()->id;

        $build = Build::create($payload);
        $build->components()->sync($componentIds);

        return redirect()->route('home')->with('status', 'Сборка сохранена и опубликована.');
    }

    public function update(Request $request, Build $build): RedirectResponse
    {
        abort_unless($request->user()->hasRole('Администратор') || $build->user_id === $request->user()->id, 403);

        [$payload, $componentIds] = $this->validatedBuildData($request);
        $build->update($payload);
        $build->components()->sync($componentIds);

        return redirect()->route('home')->with('status', 'Изменения в сборке сохранены.');
    }

    private function builderViewData(Request $request, ?Build $build): array
    {
        $components = Component::orderBy('price')->get()->groupBy('type');

        return [
            'build' => $build,
            'categories' => Category::orderBy('id')->get(),
            'cpus' => $components->get('cpu', collect()),
            'motherboards' => $components->get('motherboard', collect()),
            'gpus' => $components->get('gpu', collect()),
            'rams' => $components->get('ram', collect()),
            'psus' => $components->get('psu', collect()),
            'cases' => $components->get('case', collect()),
            'ownBuilds' => $request->user()->builds()->with('category')->latest()->get(),
        ];
    }

    /**
     * @return array{0: array<string, mixed>, 1: list<int>}
     */
    private function validatedBuildData(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'cpu_id' => ['required', 'integer', 'exists:components,id'],
            'motherboard_id' => ['required', 'integer', 'exists:components,id'],
            'gpu_id' => ['required', 'integer', 'exists:components,id'],
            'ram_id' => ['required', 'integer', 'exists:components,id'],
            'psu_id' => ['required', 'integer', 'exists:components,id'],
            'case_id' => ['required', 'integer', 'exists:components,id'],
            'description' => ['required', 'string', 'max:5000'],
        ], [
            'title.required' => 'Укажите название сборки.',
            'category_id.required' => 'Выберите категорию.',
            'description.required' => 'Добавьте описание сборки.',
        ]);

        $cpu = Component::where('type', 'cpu')->findOrFail($data['cpu_id']);
        $motherboard = Component::where('type', 'motherboard')->findOrFail($data['motherboard_id']);
        $gpu = Component::where('type', 'gpu')->findOrFail($data['gpu_id']);
        $ram = Component::where('type', 'ram')->findOrFail($data['ram_id']);
        $psu = Component::where('type', 'psu')->findOrFail($data['psu_id']);
        $pcCase = Component::where('type', 'case')->findOrFail($data['case_id']);

        $evaluation = Build::evaluateCompatibility($cpu, $motherboard, $gpu, $ram, $psu, $pcCase);

        $payload = [
            'title' => $data['title'],
            'description' => $data['description'],
            'category_id' => $data['category_id'],
            'total_price' => $evaluation['total_price'],
            'compatibility_status' => $evaluation['status'],
            'compatibility_message' => $evaluation['message'],
        ];

        $componentIds = [
            $cpu->id,
            $motherboard->id,
            $gpu->id,
            $ram->id,
            $psu->id,
            $pcCase->id,
        ];

        return [$payload, $componentIds];
    }
}
