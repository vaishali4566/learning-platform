@props(['url'])

<tr>
    <td align="center" style="
        padding: 25px 0;
        background: linear-gradient(90deg, #2563eb, #1e3a8a);
        text-align: center;
    ">
        <a href="{{ $url ?? config('app.url') }}" style="text-decoration: none;">
            <h1 style="
                font-family: 'Arial', sans-serif;
                color: #ffffff;
                font-size: 28px;
                font-weight: 700;
                margin: 0;
                letter-spacing: 1px;
            ">
                {{ config('app.name', 'MyApp') }}
            </h1>
        </a>
    </td>
</tr>
  