<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Document</title>
</head>

<body>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Courses and Purchases Summary</h1>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow">
                <thead class="bg-gray-100 border-b border-gray-300">
                    <tr>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Course ID</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Title</th>
                        <th class="text-right py-3 px-4 font-semibold text-gray-700">Number of Purchases</th>
                        <th class="text-right py-3 px-4 font-semibold text-gray-700">Total Amount Earned (RS)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($trainer->courses as $course)
                    <tr class="border-b border-gray-200 hover:bg-gray-50 transition duration-150">
                        <td class="py-3 px-4">{{ $course->id }}</td>
                        <td class="py-3 px-4">{{ $course->title }}</td>
                        <td class="py-3 px-4 text-right">{{ $course->payments_count }}</td>
                        <td class="py-3 px-4 text-right">{{ number_format($course->payments_sum_amount, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-6 text-gray-500">No courses found for this trainer.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>