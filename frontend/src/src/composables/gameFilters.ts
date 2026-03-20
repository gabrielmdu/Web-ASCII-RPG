import { ref, watch } from 'vue';
import { debounce } from 'lodash-es';

export enum GameSearchSort {
  NAME = 'name',
  CREATED_AT = 'created_at',
  LAST_MODIFIED = 'last_modified',
  CREATOR_NAME = 'creator_name',
}

export interface GameFilter {
  search: string;
  sort: GameSearchSort;
  public: boolean;
  asc: boolean;
  page: number;
}

type FetchCallback = (filters: GameFilter) => void;

const getDefaultFilters = (): GameFilter => {
  return {
    search: '',
    sort: GameSearchSort.CREATED_AT,
    public: false,
    asc: true,
    page: 1,
  };
};

export const useGameFilters = (fetchCallback: FetchCallback) => {
  const filters = ref<GameFilter>(getDefaultFilters());

  const resetFilters = function () {
    filters.value = getDefaultFilters();
  };

  const debouncedFetch = debounce(() => {
    fetchCallback(filters.value);
  }, 300);

  // if these filters change, go back to page 1
  watch(
    () => [filters.value.search, filters.value.public],
    () => {
      filters.value.page = 1;
    },
  );

  watch(filters, () => debouncedFetch(), { deep: true });

  return { filters, resetFilters };
};
