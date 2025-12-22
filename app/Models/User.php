<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable; 
use App\Notifications\CustomResetPassword;


class User extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'bio',
        'profile_image',
        'city',
        'country',
        'email_verified_at',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',                 
    ];

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class, 'trainer_id');
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPassword($token));
    }
    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }
}


// 1️⃣ Remove Duplicates & Keep Order

// Input: ArrayList<Integer>
// Output: ArrayList<Integer>
// ➡️ Hint: ArrayList + LinkedHashSet

// 2️⃣ Frequency Counter

// Input: int[]
// Output: Map<Integer, Integer>
// ➡️ Hint: HashMap + getOrDefault()

// 3️⃣ Unique Words Counter

// Input: String sentence
// Output: int
// ➡️ Hint: String.split + HashSet

// 4️⃣ Convert List to Map (Index as Key)

// Input: ArrayList<String>
// Output: Map<Integer, String>
// ➡️ Hint: HashMap

// 5️⃣ Common Elements of Two Lists

// Input: Two ArrayList<Integer>
// Output: ArrayList<Integer>
// ➡️ Hint: HashSet + retainAll()

// 6️⃣ Remove All Even Numbers

// Input: ArrayList<Integer>
// Output: ArrayList<Integer> (only odd numbers)
// ➡️ Hint: Iterator / removeIf()

// 7️⃣ Count Unique Numbers

// Input: ArrayList<Integer>
// Output: int
// ➡️ Hint: HashSet

// 8️⃣ Convert ArrayList to HashSet

// Input: ArrayList<String>
// Output: HashSet<String>
// ➡️ Hint: Constructor

// 9️⃣ Check If All Elements Are Unique

// Input: ArrayList<Integer>
// Output: boolean
// ➡️ Hint: ArrayList vs HashSet size

// 🔟 Find First Repeating Element

// Input: ArrayList<Integer>
// Output: Integer
// ➡️ Hint: HashSet

// 1️⃣1️⃣ Merge Two Lists (Remove Duplicates)

// Input: Two ArrayList<Integer>
// Output: ArrayList<Integer>
// ➡️ Hint: LinkedHashSet + addAll()

// 1️⃣2️⃣ Convert Map Keys to List

// Input: Map<Integer, String>
// Output: ArrayList<Integer>
// ➡️ Hint: keySet()

// 1️⃣3️⃣ Convert Map Values to List

// Input: Map<Integer, String>
// Output: ArrayList<String>
// ➡️ Hint: values()

// 1️⃣4️⃣ Reverse a List

// Input: ArrayList<Integer>
// Output: ArrayList<Integer>
// ➡️ Hint: Collections.reverse()

// 1️⃣5️⃣ Sort List of Strings

// Input: ArrayList<String>
// Output: Sorted ArrayList
// ➡️ Hint: Collections.sort()

// 1️⃣6️⃣ Count Word Frequency

// Input: String sentence
// Output: Map<String, Integer>
// ➡️ Hint: HashMap + split()

// 1️⃣7️⃣ Check Two Lists Are Equal (Order Matters)

// Input: Two ArrayList<Integer>
// Output: boolean
// ➡️ Hint: equals()

// 1️⃣8️⃣ Check Two Lists Have Same Elements (Order Doesn’t Matter)

// Input: Two ArrayList<Integer>
// Output: boolean
// ➡️ Hint: HashSet

// 1️⃣9️⃣ Remove Duplicates Without Using Set

// Input: ArrayList<Integer>
// Output: ArrayList<Integer>
// ➡️ Hint: contains()

// 2️⃣0️⃣ Convert List to Queue

// Input: ArrayList<Integer>
// Output: Queue<Integer>
// ➡️ Hint: ArrayDeque

// 2️⃣1️⃣ Find Max Element Using Collections

// Input: ArrayList<Integer>
// Output: int
// ➡️ Hint: Collections.max()

// 2️⃣2️⃣ Find Min Element Using Collections

// Input: ArrayList<Integer>
// Output: int
// ➡️ Hint: Collections.min()

// 2️⃣3️⃣ Remove Null Values from List

// Input: ArrayList<String>
// Output: ArrayList<String>
// ➡️ Hint: removeIf()

// 2️⃣4️⃣ Convert Set to List

// Input: HashSet<Integer>
// Output: ArrayList<Integer>
// ➡️ Hint: Constructor

// 2️⃣5️⃣ Find Duplicate Elements

// Input: ArrayList<Integer>
// Output: Set<Integer>
// ➡️ Hint: HashSet


// 🟢 EASY LEVEL (Basics + Confidence Build)
// 1️⃣ Remove Duplicates & Keep Order

// Input: ArrayList<Integer>
// Task: Duplicate remove karo but order maintain rahe

// ➡️ Hint: ArrayList + LinkedHashSet

// 2️⃣ Frequency Counter

// Input: int[]
// Output: Map<Integer, Integer>

// ➡️ Hint: HashMap + getOrDefault()

// 3️⃣ Unique Words Counter

// Input: String sentence
// Output: Number of unique words

// ➡️ Hint: String.split + HashSet

// 4️⃣ Convert List to Map (Index as Key) 

// Input: ArrayList<String>
// Output: Map<Integer, String>

// ➡️ Hint: HashMap

// 5️⃣ Common Elements

// Input: Two ArrayLists
// Output: List of common elements

// ➡️ Hint: HashSet + retainAll()

// 🟡 MEDIUM LEVEL (Real Use-Cases)
// 6️⃣ Group Elements by Frequency

// Input: int[]
// Output: Map<Integer, List<Integer>>
// (Frequency → numbers)

// ➡️ Hint: HashMap + ArrayList

// 7️⃣ Sort Elements by Frequency

// Input: int[]
// Output: List<Integer> sorted by frequency

// ➡️ Hint:
// HashMap + ArrayList + Collections.sort

// 8️⃣ First Non-Repeating Character

// Input: String
// Output: Character

// ➡️ Hint:
// LinkedHashMap (order important)

// 9️⃣ Merge Two Maps (Sum Values)

// Input: Map<Integer,Integer> m1, m2
// Output: Combined Map

// ➡️ Hint:
// HashMap + getOrDefault

// 🔟 Remove Elements Present in Set

// Input: List + Set
// Output: Filtered List

// ➡️ Hint:
// Iterator or removeIf()

// 🔴 HARD LEVEL (Interview + DSA Style)
// 1️⃣ Top K Frequent Elements

// Input: int[] , k
// Output: List<Integer>

// ➡️ Hint:
// HashMap + PriorityQueue

// 1️⃣2️⃣ LRU Cache (Basic)

// Operations: get, put

// ➡️ Hint:
// LinkedHashMap

// 1️⃣3️⃣ Group Anagrams

// Input: List<String>
// Output: List<List<String>>

// ➡️ Hint:
// HashMap<String, ArrayList<String>>

// 1️⃣4️⃣ Flatten Nested List

// Input: [1,[2,[3]]]
// Output: [1,2,3]

// ➡️ Hint:
// Stack / Recursion + ArrayList

// 1️⃣5️⃣ Sliding Window Maximum

// Input: int[] , k
// Output: int[]

// ➡️ Hint:
// Deque (ArrayDeque)

// 1️⃣6️⃣ Word Frequency Sort

// Input: String paragraph
// Output: Words sorted by frequency

// ➡️ Hint:
// HashMap + PriorityQueue

// 1️⃣7️⃣ Task Scheduler

// Input: Tasks with priority
// Output: Execution order

// ➡️ Hint:
// PriorityQueue + Map