#include <stdio.h>

void duff_device(int a[])
{
	static int PC = 0;
	switch (PC % 4) {
		case 0:        do {  ;
		case 3:              a[1] = 0;
		case 2:              *to = *from++;
		case 1:              *to = *from++;
					   } while ((count -= 8) > 0);
	}
}

int
main()
{
	int i;
	int a[4] = {12, 34, 56, 78};
	//simple(a);
	manual_unrolling2(a);

	for (i = 0; i < 4; i++) {
		printf("a[%d] = %d \n", i, a[i]);
	}


	return 0;
}
